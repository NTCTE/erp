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
            $exists = Machine::where('ip_address', $address);
            if ($exists -> count() == 0) {
                $machine = new Machine();
                $machine -> ip_address = $address;
                $machine -> save();
                return response() -> json([
                    'id' => $machine -> id,
                ], 200);
            } else {
                $machine = $exists -> first();
                return response() -> json([
                    'id' => $machine -> id,
                ], 200);
            }
        } else return response() -> json([
            'message' => 'Не указан IP-адрес машины',
        ], 400);
    }
   
    /**
     * Получить список команд для машины.
     *
     * @param  int $id Идентификатор машины.
     * @return JsonResponse Ответ.
     */
    public function get_commands(int $id = null): JsonResponse {
        if ($id) {
            $machine = Machine::find($id);
            if ($machine) {
                return response() -> json([
                    'commands' => $machine -> not_executed_commands(),
                ], 200);
            } else return response() -> json([
                'message' => 'Машина не найдена',
            ], 404);
        } else return response() -> json([
            'message' => 'Не указан идентификатор машины',
        ], 400);
    }
    
    /**
     * Сообщение машиной о выполнении команды.
     *
     * @param  int $id Идентификатор машины.
     * @return JsonResponse Ответ.
     */
    public function set_command(int $id = null): JsonResponse {
        if ($id) {
            $request = request() -> validate([
                'id' => 'required|numeric',
                'exit_code' => 'required|numeric',
            ], [
                'id.required' => 'Не указан идентификатор команды',
                'id.numeric' => 'Идентификатор команды указан неверно',
                'exit_code.required' => 'Не указан код завершения команды',
                'exit_code.numeric' => 'Код завершения команды указан неверно',
            ]);
            $machine = Machine::find($id);
            if ($machine) {
                if ($machine -> executed_commands -> where('id', $request['id']) -> count() == 0) {
                    $command = Command::where('id', $request['id']) -> first();
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
            'message' => 'Не указан идентификатор машины',
        ], 400);
    }
}
