<?php

namespace App\Filament\Resources\CoursesResource\Pages;

use App\Filament\Resources\CoursesResource;
use App\Models\CourseInformation;
use App\Models\Language;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Mews\Purifier\Facades\Purifier;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class EditCourses extends EditRecord
{
    protected static string $resource = CoursesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $info_ar = $this->record->information()->where('language_id', Language::query()->where('code', 'ar')->first()->id)->first();
        $info_en = $this->record->information()->where('language_id', Language::query()->where('code', 'en')->first()->id)->first();

        $data['certificate_title_en'] = $info_en->title;
        $data['meta_keywords_en'] = $info_en->meta_keywords;
        $data['category_id_en'] = $info_en->course_category_id;
        $data['meta_description_en'] = $info_en->meta_description;

        $data['certificate_text_en'] = $info_en->description;
        $data['certificate_text_ar'] = $info_ar->description;
        
        $data['features_ar'] = json_decode($info_ar->features);
        $data['features_en'] = json_decode($info_en->features);


        $data['certificate_title_ar'] = $info_ar->title;
        $data['meta_keywords_ar'] = $info_ar->meta_keywords;
        $data['category_id_ar'] = $info_ar->course_category_id;
        $data['meta_description_ar'] = $info_ar->meta_description;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $languages = Language::all();

        foreach ($languages as $language) {
            $data['course_informations_' . $language->code]['language_id'] = $language->id;
            $data['course_informations_' . $language->code]['course_category_id'] = $data['category_id_' . $language->code];
            $data['course_informations_' . $language->code]['title'] = $data['certificate_title_' . $language->code];
            $data['course_informations_' . $language->code]['slug'] = $this->createSlug($data['certificate_title_' . $language->code]);
            $data['course_informations_' . $language->code]['features'] = $data['features_' . $language->code];
            $data['course_informations_' . $language->code]['description'] = $data['certificate_text_' . $language->code];
            $data['course_informations_' . $language->code]['meta_keywords'] = $data['meta_keywords_' . $language->code] ?? null;
            $data['course_informations_' . $language->code]['meta_description'] = $data['meta_description_' . $language->code] ?? null;
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $course, array $data): Model
    {
        $course->update(
            collect($data)->except('course_informations_ar', 'course_informations_en')->toArray()
        );

        foreach (['ar', 'en'] as $lang) {
            CourseInformation::updateOrCreate(
                ['course_id' => $course->id, 'language_id' => Language::where('code', $lang)->first()->id],
                $data['course_informations_' . $lang]
            );
        }

        return $course;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $course = $this->record;
        $title = $course->information->where('language_id', Language::where('code', 'en')->first()->id)->first()->title;

        Notification::make()
            ->title('Course Updated')
            ->icon('heroicon-o-video-camera')
            ->body("**{$course->title} has been updated.**")
            ->actions([
                Action::make('View')
                    ->url(CoursesResource::getUrl('edit', ['record' => $course]))
            ])
            ->sendToDatabase(auth()->guard('admin')->user());
    }

    private function createSlug($string)
    {
        $slug = preg_replace('/\s+/u', '-', trim($string));
        $slug = str_replace(['/', '?', ','], '', $slug);

        return mb_strtolower($slug);
    }

}
