<?php

namespace App\Http\Business;

use App\Exceptions\CreateEventException;
use App\Models\Event;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Storage;
use Str;
use Throwable;
use Validator;

class EventBusiness
{
    // Rules to be used when validating data.
    const rules = [
        'user_id' => 'required|exists:users,id',
        'team_id' => 'required|exists:teams,id',
        'name' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
        'datetime' => 'required|date',
        'location' => 'required|string|max:255',
        'banner' => 'required|string|max:255',
    ];

    // Messages to be returned when validation fails.
    const customMessages = [
        'user_id.required' => 'O campo user_id é obrigatório.',
        'user_id.exists' => 'O usuário especificado não existe.',
        'team_id.required' => 'O campo team_id é obrigatório.',
        'team_id.exists' => 'O time especificado não existe.',
        'capacity.required' => 'O campo capacidade é obrigatório.',
        'capacity.integer' => 'O campo capacidade deve ser um número inteiro.',
        'capacity.min' => 'O campo capacidade deve ser no mínimo 1.',
        'name.required' => 'O campo nome é obrigatório.',
        'name.max' => 'O campo nome não pode ter mais de 255 caracteres.',
        'datetime.required' => 'O campo data e hora é obrigatório.',
        'datetime.date' => 'O campo data e hora deve ser uma data e hora válida.',
        'location.required' => 'O campo localização é obrigatório.',
        'location.max' => 'O campo localização não pode ter mais de 255 caracteres.',
        'banner.required' => 'O campo banner é obrigatório.',
        'banner.max' => 'O campo banner não pode ter mais de 255 caracteres.', // Ajuste o tamanho conforme necessário
    ];

    /**
     * Create a new Event instance and return it.
     * @throws ValidationException
     * @throws Throwable
     */
    public static function createEvent($data): Model|Event
    {
        // Get the validated data
        $validParams = Validator::validate($data, EventBusiness::rules, EventBusiness::customMessages);
        $event = null;
        DB::transaction(function () use ($validParams, &$event) {
            $bannerPath = 'banners';
            $event = Event::create($validParams);
            // Make the upload of the banner.
            if (isset($validParams['banner'])) {
                // Generate a UUID for the banner.
                $uuid = Str::uuid();
                if (Storage::put("/$bannerPath/$uuid", $validParams['banner'])) {
                    $event->banner = $uuid;
                    try {
                        $event->save(); // Save the event.
                    } catch (Throwable $e) { // If the save fails, delete the banner and throw an exception.
                        // Delete the banner, if it exists.
                        if ($event->banner) {
                            Storage::delete("/$bannerPath/$event->banner");
                        }
                        throw $e;
                    }
                } else { // If the upload fails, rollback the transaction and throw an exception.
                    throw new CreateEventException('Não foi possível fazer o upload do banner!');
                }
            }
        }, 2); // The second parameter is the number of times the transaction should be retried.
        return $event;
    }

    /**
     * Update an event and return it.
     * @param $event  - Event to be updated.
     * @param $data  - Event data.
     * @return mixed - Event updated.
     */
    public static function updateEvent($event, $data): mixed
    {
        $event->update($data);

        // Update the banner, if it exists.
        if (isset($data['banner'])) {
            // Delete the previous banner, if it exists.
            if ($event->banner_path) {
                // Delete the previous banner.
                Storage::disk('s3')->delete($event->banner_path);
            }

            // Upload the new banner.
            $bannerPath = $data['banner']->store('banners', 's3');
            $event->banner_path = $bannerPath;
            $event->save();
        }

        return $event;
    }

    /**
     * Delete an event.
     * @param $event  - Event to be deleted.
     * @return void - Nothing.
     */
    public static function deleteEvent($event): void
    {
        // Delete the banner, if it exists.
        if ($event->banner_path) {
            Storage::disk('s3')->delete($event->banner_path);
        }

        $event->delete();
    }

    /**
     * Get the events that are available.
     * @return array|Collection - Events available.
     */
    public static function getAvailableEvents(): array|Collection
    {
        return Event::where('datetime', '>=', now())->get();
    }

    /**
     * Get the events that are available and editable by the user. The user can edit an
     * event if it belongs to a team and the event not happened yet. The user not can edit the event on day event.
     * @return Event[]|Builder[]|Collection
     */
    public static function getMyEditableEvents(): Collection|array
    {
        return Event::where('datetime', '>', now())->where('team_id', getPermissionsTeamId())->get();
    }
}
