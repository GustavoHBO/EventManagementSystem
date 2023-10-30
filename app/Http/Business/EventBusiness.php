<?php

namespace App\Http\Business;

use App\Exceptions\CreateEventException;
use App\Models\Event;
use Auth;
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
        'datetime' => 'required|date',
        'location' => 'required|string|max:255',
        'banner' => [
            'required',
            'regex:/^data:image\/(jpeg|jpg|png|gif|webp);base64,/i',
            'max:'. 5 * (10 ** 6) // Maximum image size in bytes (e.g., 10MB)
        ],
        'lots' => 'required|array|min:1',
        'lots.*.name' => 'required|string|max:255',
        'lots.*.available_tickets' => 'required|integer|min:0',
        'lots.*.expiration_date' => 'required|date|date_format:Y-m-d',
        'lots.*.ticket_prices' => 'required|array|min:1',
        'lots.*.ticket_prices.*.sector_id' => 'required_without:lots.*.ticket_prices.*.sector|exists:sectors,id',
        'lots.*.ticket_prices.*.sector' => 'required_without:lots.*.ticket_prices.*.sector_id',
        'lots.*.ticket_prices.*.price' => 'required|numeric|min:0'
    ];

    // Messages to be returned when validation fails.
    const customMessages = [
        'user_id.required' => 'O campo user_id é obrigatório.',
        'user_id.exists' => 'O usuário especificado não existe.',
        'team_id.required' => 'O campo team_id é obrigatório.',
        'team_id.exists' => 'O time especificado não existe.',
        'name.required' => 'O campo nome é obrigatório.',
        'name.max' => 'O campo nome não pode ter mais de 255 caracteres.',
        'datetime.required' => 'O campo data e hora é obrigatório.',
        'datetime.date' => 'O campo data e hora deve ser uma data e hora válida.',
        'location.required' => 'O campo localização é obrigatório.',
        'location.max' => 'O campo localização não pode ter mais de 255 caracteres.',
        'banner.regex' => 'O formato do banner não é suportado.',
        'banner.required' => 'O campo banner é obrigatório.',
        'banner.max' => 'O campo banner não deve exceder 5MB de tamanho.',
        'lots.required' => 'Pelo menos um lote é obrigatório.',
        'lots.array' => 'Os lotes devem ser um array.',
        'lots.*.name.required' => 'O nome do lote é obrigatório.',
        'lots.*.name.string' => 'O nome do lote deve ser uma string.',
        'lots.*.name.max' => 'O nome do lote não pode exceder 255 caracteres.',
        'lots.*.available_tickets.required' => 'Os ingressos disponíveis para o lote são obrigatórios.',
        'lots.*.available_tickets.integer' => 'Os ingressos disponíveis devem ser um número inteiro.',
        'lots.*.available_tickets.min' => 'Os ingressos disponíveis devem ser um número inteiro não negativo.',
        'lots.*.expiration_date.required' => 'A data de validade do lote é obrigatória.',
        'lots.*.expiration_date.date' => 'A data de validade do lote deve ser uma data válida.',
        'lots.*.expiration_date.date_format' => 'A data de validade do lote deve estar no formato Y-m-d.',
        'lots.*.ticket_prices.required' => 'Pelo menos um preço de ingresso é obrigatório para cada lote.',
        'lots.*.ticket_prices.array' => 'Os preços de ingressos devem ser um array para cada lote.',
        'lots.*.ticket_prices.*.sector_id.required' => 'O ID do setor é obrigatório para cada preço de ingresso.',
        'lots.*.ticket_prices.*.sector_id.integer' => 'O ID do setor deve ser um número inteiro para cada preço de ingresso.',
        'lots.*.ticket_prices.*.sector_id.exists' => 'O setor informado não existe.',
        'lots.*.ticket_prices.*.sector_id.required_without' => 'Especifique um ID de setor ou forneça dados para criar um novo setor.',
        'lots.*.ticket_prices.*.sector.required_without' => 'Especifique um setor ou forneça um ID de setor existente.',
        'lots.*.ticket_prices.*.price.required' => 'O preço é obrigatório para cada preço de ingresso.',
        'lots.*.ticket_prices.*.price.numeric' => 'O preço deve ser um valor numérico para cada preço de ingresso.',
        'lots.*.ticket_prices.*.price.min' => 'O preço deve ser um valor numérico não negativo para cada preço de ingresso.'
    ];

    /**
     * Create a new Event instance and return it.
     * @throws ValidationException - If the data is invalid.
     * @throws Throwable - If the transaction fails.
     */
    public static function createEvent($data): Model|Event
    {
        // Get the validated data
        $data['user_id'] = Auth::user()->id;
        $data['team_id'] = getPermissionsTeamId();
        $validParams = Validator::validate($data, EventBusiness::rules, EventBusiness::customMessages);
        $event = null;
        DB::transaction(function () use ($validParams, &$event) {
            $bannerPath = 'banners';
            // Make the upload of the banner.
            if (isset($validParams['banner'])) {
                // Generate a UUID for the banner.
                $uuid = Str::uuid()->toString();
                if (Storage::put("/$bannerPath/$uuid", $validParams['banner'])) {
                    $validParams['banner'] = $uuid;
                    $event = Event::create($validParams);
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

            // Insert the lots.
            foreach ($validParams['lots'] as $lot) {
                $lot['event_id'] = $event->id;
                $lotCreated = LotBusiness::createLot($lot);
                $sector = null;
                foreach ($lot['ticket_prices'] as $ticketPrice) {
                    if (isset($ticketPrice['sector'])) {
                        $sector = SectorBusiness::createSector($ticketPrice['sector']);
                    } elseif (isset($ticketPrice['sector_id'])) {
                        $sector = SectorBusiness::getSectorById($ticketPrice['sector_id']);
                    }
                    LotBusiness::attachSector($lotCreated, $sector);
                    $ticketPrice['sector_id'] = $sector->id ?? null;
                    $ticketPrice['lot_id'] = $lotCreated->id;
                    TicketBusiness::createTicketPrice($ticketPrice);
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
     * @return Builder|Event - Events available.
     */
    public static function getAvailableEvents(): Event|Builder
    {
        return Event::where('datetime', '>=', now());
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
