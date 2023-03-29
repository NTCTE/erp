<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\System\Machines\Command;
use App\Models\System\Machines\ExecutedCommand;
use App\Models\System\Machines\Machine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * MachinesController - контроллер API для работы с машинами и рассылки команд на них по требованию.
 */
class MachinesController extends Controller
{    
    /**
     * Регистрация машины в системе.
     *
     * @param  Request $request Запрос.
     * @return JsonResponse Ответ.
     */
    public function register(Request $request): JsonResponse {
        $address = $request -> input('address');
        if ($address) {
            if (Machine::where('ip_address', $address) -> count() == 0) {
                $machine = new Machine();
                $machine -> ip_address = $address;
                $machine -> save();
                return response() -> json([
                    'token' => $machine -> uuid,
                ], 200);
            } else return response() -> json([
                'message' => 'Машина уже зарегистрирована',
            ], 400);
        } else return response() -> json([
            'message' => 'Не указан IP-адрес машины',
        ], 400);
    }
   
    /**
     * Получить список команд для машины.
     *
     * @param  mixed $uuid UUID машины.
     * @return JsonResponse Ответ.
     */
    public function get_commands(string $uuid = null): JsonResponse {
        if ($uuid) {
            $machine = Machine::where('uuid', $uuid) -> first();
            if ($machine) {
                return response() -> json([
                    'commands' => $machine -> not_executed_commands(),
                ], 200);
            } else return response() -> json([
                'message' => 'Машина не найдена',
            ], 404);
        } else return response() -> json([
            'message' => 'Не указан UUID машины',
        ], 400);
    }
    
    /**
     * Сообщение машиной о выполнении команды.
     *
     * @param  string $uuid UUID машины.
     * @return JsonResponse Ответ.
     */
    public function set_command(string $uuid = null): JsonResponse {
        if ($uuid) {
            $request = request() -> validate([
                'uuid' => 'required|uuid',
                'exit_code' => 'required|integer',
            ], [
                'uuid.required' => 'Не указан UUID команды',
                'uuid.uuid' => 'UUID команды указан неверно',
                'exit_code.required' => 'Не указан код завершения команды',
                'exit_code.integer' => 'Код завершения команды указан неверно',
            ]);
            $machine = Machine::where('uuid', $uuid) -> first();
            if ($machine) {
                if ($machine -> executed_commands -> where('uuid', $request['uuid']) -> count() == 0) {
                    $command = Command::where('uuid', $request['uuid']) -> first();
                    if ($command) {
                        (new ExecutedCommand)
                            -> fill([
                                'machine_id' => $machine -> id,
                                'command_id' => $command -> id,
                                'exit_code' => $request['exit_code'],
                            ])
                            -> save();
                        return response() -> json([
                            'message' => 'Команда успешно выполнена',
                        ], 200);
                    } else return response() -> json([
                        'message' => 'Команда не найдена',
                    ], 400);
                } else return response() -> json([
                    'message' => 'Команда уже выполнена',
                ], 400);
            } else return response() -> json([
                'message' => 'Машина не найдена',
            ], 400);
        } else return response() -> json([
            'message' => 'Не указан UUID машины',
        ], 400);
    }
}
