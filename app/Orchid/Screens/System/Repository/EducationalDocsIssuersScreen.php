<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\EducationalDocIssuer;
use App\Orchid\Layouts\System\Repository\EducationalDocsIssuersTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EducationalDocsIssuersScreen extends Screen
{
    public $issuers;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(EducationalDocIssuer $issuers): iterable
    {
        return [
            'issuers' => $issuers -> paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Места выдачи документов об образовании';
    }

    public function description(): ?string
    {
        return 'Репозиторий мест выдачи документов об образовании. Обычно, данные в этом репозитории заполняются через соотвествующие формы. При необходимости, вы можете дополнить данные в этом окне.';
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
                -> icon('plus')
                -> modal('modalCreate')
                -> method('create'),
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
            Layout::modal('modalCreate', [
                Layout::rows([
                    Input::make('fullname')
                        -> title('Полное наименование')
                        -> placeholder('Введите полное наименование')
                        -> required(),
                ]),
            ])
                -> title('Добавить место выдачи документов об образовании')
                -> applyButton('Добавить')
                -> withoutCloseButton(),
            EducationalDocsIssuersTable::class,
        ];
    }

    public function create(Request $request, EducationalDocIssuer $issuer)

    {
        $validator = Validator::make($request->all(), [
            'fullname' => ['required', 'string', 'max:255'],
        ], [
            'fullname.required' => 'Поле обязательно для заполнения',
            'fullname.string' => 'Значение должно быть строкой',
            'fullname.max' => 'Значение не должно превышать 255 символов',
        ]);

        if ($validator->fails()) {
            Toast::error($validator->errors()->first());
            return;
        }

        $issuer->fill([
            'fullname' => $request->input('fullname'),
        ]);
        $issuer->save();

        Toast::success('Место выдачи документов об образовании успешно добавлено');
    }
}
