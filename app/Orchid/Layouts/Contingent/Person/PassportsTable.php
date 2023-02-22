<?php

namespace App\Orchid\Layouts\Contingent\Person;

use App\Models\Org\Contingent\Passport;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PassportsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'passports';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        $this -> title('Паспорта');
        return [
            TD::make('full_number', 'Серия и номер'),
            TD::make('passport_issuer', 'Кем выдан')
                -> width('30%'),
            TD::make('date_of_issue', 'Дата выдачи'),
            TD::make('birthplace', 'Место рождения'),
            TD::make('is_main', 'Основной'),
            TD::make('actions', 'Действия')
                -> render(function(Passport $passport) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            Button::make('Сделать основным')
                                -> method('makeMainPassport', [
                                    'passport_id' => $passport -> id,
                                ]),
                            Button::make('Удалить паспорт')
                                -> method('removePassport', [
                                    'passport_id' => $passport -> id,
                                ])
                                -> confirm('Вы уверены, что хотите удалить паспорт? После удаления его нельзя будет восстановить.'),
                            Button::make('Редактировать')
                                -> method('editPassport', [
                                    'passport_id' => $passport -> id,
                                ]),
                        ]);
                })
                -> canSee(Auth::user() -> hasAccess('org.contingent.write')),
        ];
    }
}
