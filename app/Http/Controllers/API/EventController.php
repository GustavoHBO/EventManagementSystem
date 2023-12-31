<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CreateEventException;
use App\Http\Business\EventBusiness;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
//        dd(new EventResource(Event::find(11)));
        // Get all events.
//        $events = Event::all();

        // Get the available events.
        $events = EventBusiness::getAvailableEvents()->paginate();
//        $events = EventBusiness::getMyEditableEvents();
        return $this->sendSuccessResponse([
            'events' => EventResource::collection($events),
            'meta' => [
                'current_page' => $events->currentPage(),
                'from' => $events->firstItem(),
                'last_page' => $events->lastPage(),
                'per_page' => $events->perPage(),
                'to' => $events->lastItem(),
                'total' => $events->total(),
            ],
            'links' => [
                'first' => $events->url(1),
                'last' => $events->url($events->lastPage()),
                'next' => $events->nextPageUrl(),
                'prev' => $events->previousPageUrl(),
            ],
        ], 'Eventos recuperados com sucesso!');
    }

    /**
     * Display the specified event.
     * @param $id  - Event ID.
     * @return JsonResponse - Event data.
     */
    public function show($id): JsonResponse
    {
        // Get the event by ID.
        $event = Event::find($id);

        // Check if the event was found.
        if (!$event) {
            return $this->sendErrorResponse('Evento não encontrado!', 404);
        }

        // Return the event as a JSON response.
        return $this->sendSuccessResponse(new EventResource($event), 'Evento recuperado com sucesso!');
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('event create')) {
            return $this->sendErrorResponse('Você não tem permissão para criar eventos!', 403);
        }
        try {
            // Create a new event.
            $event = EventBusiness::createEvent($request->all());
            // Return the event as a JSON response.
            return $this->sendSuccessResponse(new EventResource($event), 'Evento criado com sucesso!', 201);
        } catch (Throwable|CreateEventException $e) {
            return $this->sendErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified event by ID.
     * @param  Request  $request  - Request data.
     * @param $id  - Event ID.
     * @return JsonResponse - Event data.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('update events')) {
            return $this->sendErrorResponse('Você não tem permissão para atualizar eventos!', 403);
        }

        // Get the event by ID.
        $event = Event::find($id);

        // Verify if the event was found.
        if (!$event) {
            return $this->sendErrorResponse('Evento não encontrado!', 404);
        }
        $event = EventBusiness::updateEvent($event, $request->all());
        // Return the event as a JSON response.
        return $this->sendSuccessResponse(new EventResource($event), 'Evento atualizado com sucesso!');
    }

    /**
     * Remove the specified event by ID.
     * @param $id  - Event ID.
     * @return JsonResponse - Success message.
     */
    public function destroy($id): JsonResponse
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('event delete')) {
            return $this->sendErrorResponse('Você não tem permissão para excluir eventos!', 403);
        }
        // Get the event by ID.
        $event = Event::find($id);

        // Verify if the event was found.
        if (!$event) {
            return $this->sendErrorResponse('Evento não encontrado!', 404);
        }

        // Delete the event.
        EventBusiness::deleteEvent($event);

        // Return success message.
        return $this->sendSuccessResponse(null, 'Evento excluído com sucesso!');
    }
}
