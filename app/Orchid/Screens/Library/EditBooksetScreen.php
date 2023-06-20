<?php

namespace App\Orchid\Screens\Library;

use App\Models\Org\Library\Additionals\Author;
use App\Models\Org\Library\Additionals\BookSetType;
use App\Models\Org\Library\Additionals\PertainingTitleInformation;
use App\Models\Org\Library\Additionals\PublicationInformation;
use App\Models\Org\Library\Additionals\Publisher;
use App\Models\Org\Library\Additionals\SubjectHeadline;
use App\Models\Org\Library\BookSet;
use App\Models\System\Repository\AdministrativeDocument;
use App\Models\System\Repository\Language;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditBooksetScreen extends Screen
{
    public $bookset;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(BookSet $bookSet): iterable
    {
        return [
            'bookset' => $bookSet,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->bookset->exist ? 'Редактировать комплект книг' : 'Добавить новый комплект книг';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Сохранить')
                ->icon('save')
                ->confirm('Вы уверены, что хотите сохранить новый комплект книг?')
                ->method('saveBookSet')
                ->canSee(!$this->bookset->exists),

            Button::make('Обновить')
                ->icon('note')
                ->method('saveBookSet')
                ->canSee($this->bookset->exists),
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
            Layout::rows([
                Input::make('bookset.title')
                    ->title('Заглавие')
                    ->placeholder('Введите заглавие')
                    ->horizontal()
                    ->required(),
                Input::make('bookset.cost')
                    ->title('Стоимость одного экземпляра')
                    ->required(),
                Relation::make('bookset.book_set_type_id')
                    ->fromModel(BookSetType::class, 'fullname')
                    ->title('Тип комплекта')
                    ->required(),
                Relation::make('bookset.pertaining_title_information_id')
                    ->title('Информация, относящаяся к заглавию')
                    ->fromModel(PertainingTitleInformation::class, 'fullname')
                    ->required(),
                DateTimer::make('bookset.publishing_year')
                ->title('Год издания')
                ->placeholder('')
                ->format('Y')
                ->allowInput()
                ->required(),
                Relation::make('bookset.publication_information_id')
                    ->title('Информация об издании')
                    ->fromModel(PublicationInformation::class, 'fullname')
                    ->required(),
                Relation::make('bookset.publisher_id')
                    ->title('Издатель')
                    ->fromModel(Publisher::class, 'fullname')
                    ->required(),
                Relation::make('bookset.author_id')
                    ->title('Автор')
                    ->fromModel(Author::class, 'lastname')
                    ->displayAppend('Fullname')
                    ->searchColumns('firstname', 'patronymic')
                    ->required(),
                Input::make('bookset.isbn')
                    ->title('ISBN')
                    ->required(),
                Input::make('bookset.pages_number')
                    ->type('number')
                    ->title('Количество страниц')
                    ->required(),
                TextArea::make('bookset.annotation')
                    ->title('Аннотация')
                    ->rows(5)
                    ->required(),
                Relation::make('bookset.subject_headline_id')
                    ->title('Предметный заголовок')
                    ->fromModel(SubjectHeadline::class, 'fullname')
                    ->required(),
                Relation::make('bookset.language_id')
                    ->title('Язык')
                    ->fromModel(Language::class, 'fullname')
                    ->required(),
                Relation::make('bookset.basis_doc_id')
                    ->title('Основание поступления документа')
                    ->fromModel(AdministrativeDocument::class, 'fullname')
                    ->searchColumns('type', 'number', 'date_at')
                    ->displayAppend('Formatted')
                    ->required(),
                Input::make('bookset.barcode')
                    ->title('Штрих-код')
                    ->required(),
                Picture::make('bookset.cover_id')
                    ->title('Обложка')
                    ->storage('public')
                    ->acceptedFiles('.jpg')
                    ->horizontal()
                    ->targetId(),
                Upload::make('bookset.digitized_id')
                    ->title('Цифровизированный экземпляр')
                    ->maxFileSize(4)
                    ->acceptedFiles('.pdf')
                    ->storage('public')
                    ->maxFiles(1)
                    ->horizontal()
            ])
        ];
    }

    public function saveBookSet(Request $request, BookSet $bookSet)
    {

        $bookSet->fill($request->get('bookset'));

        if ($request->has('bookset.digitized_id')) {
            $digitized_id = $request->input('bookset.digitized_id');
            $bookSet->fill([
                'digitized_id' => array_shift($digitized_id)
            ]);
        }

        $bookSet->save();

        Toast::success('Данные о наборе успешно сохранены!');

        return redirect()
            ->route('library.booksets');
    }

}
