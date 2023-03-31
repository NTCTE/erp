<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\Workplace;
use App\Orchid\Layouts\System\Repository\WorkplaceTable;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class WorkplaceScreen extends Screen
{
    public $workplace;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Workplace $workplace): iterable
    {
        return [
            'workplace' => $workplace::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Рабочие места';
    }

    public function description(): ?string
    {
        return 'Обычно, отсюда работать не нужно, таблица "Рабочие места" заполняется автоматически. Если есть жизненная необходимость, то добавьте отсюда. Но зачем?';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить')
                -> modal('workplaceCreate')
                -> method('create')
                -> icon('plus'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::modal('workplaceCreate', [
                Layout::rows([
                    Input::make('wp.fullname')
                        -> title('Наименование')
                        -> placeholder('Введите наименование...')
                        -> required(),
                    Input::make('wp.tel')
                        -> title('Телефон')
                        -> placeholder('Введите телефон...')
                        -> type('tel')
                        -> mask([
                            'mask' => '+7 (999) 999 99-99'
                        ]),
                    Input::make('wp.email')
                        -> title('Адрес электронной почты')
                        -> placeholder('Введите адрес электронной почты...')
                        -> type('email'),

                ]),
            ])
                -> title('Добавить рабочее место')
                -> applyButton('Добавить')
                -> withoutCloseButton(),
            WorkplaceTable::class,
        ];
    }

    public function create(Request $request, Workplace $wp) {

        $request -> validate([
            'wp.fullname' => 'required|string|max:255',
            'wp.tel' => 'nullable|string|max:20',
            'wp.email' => 'nullable|email|max:150',
            ],[
            'wp.fullname.required' => 'Введите полное "рабочее место"',
            'wp.fullname.string' => '"рабочее место" должно быть строкой',
            'wp.fullname.max' => '"рабочее место" не должно превышать 255 символов',
            'wp.tel.string' => 'Номер телефона должен быть строкой',
            'wp.tel.max' => 'Номер телефона не должен превышать 20 символов',
            'wp.email.email' => 'Неправильный формат email',
            'wp.email.max' => 'Email не должен превышать 255 символов',
            ]);


        $wp -> fill(request() -> input('wp'))
            -> save();

        Toast::success('Рабочее место успешно добавлено.');
    }
}
