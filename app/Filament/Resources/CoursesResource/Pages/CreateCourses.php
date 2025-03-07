<?php

namespace App\Filament\Resources\CoursesResource\Pages;

use App\Filament\Resources\CoursesResource;
use App\Models\CourseInformation;
use App\Models\Language;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Mews\Purifier\Facades\Purifier;
use Filament\Notifications\Actions\Action;

class CreateCourses extends CreateRecord
{
    protected static string $resource = CoursesResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $languages = Language::all();

        foreach ($languages as $language) {
            $data['course_informations_' . $language->code]['language_id'] = $language->id;
            $data['course_informations_' . $language->code]['course_category_id'] = $data['category_id_' . $language->code];
            $data['course_informations_' . $language->code]['title'] = $data['certificate_title_' . $language->code];
            $data['course_informations_' . $language->code]['slug'] = $this->createSlug($data['certificate_title_' . $language->code]);
            $data['course_informations_' . $language->code]['features'] = json_encode($data['features_' . $language->code]);
            $data['course_informations_' . $language->code]['description'] = $data['certificate_text_'. $language->code];
            $data['course_informations_' . $language->code]['meta_keywords'] = $data['meta_keywords_' . $language->code] ?? null;
            $data['course_informations_' . $language->code]['meta_description'] = $data['meta_description_' . $language->code] ?? null;
        }

        return $data;
    }


    protected function handleRecordCreation(array $data): Model
    {
        $course = static::getModel()::create(
            collect($data)->except('course_informations_ar', 'course_informations_en')->toArray()
        );

        CourseInformation::insert([
            $data['course_informations_ar'] + ['course_id' => $course->id],
            $data['course_informations_en'] + ['course_id' => $course->id],
        ]);

        return $course;
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $course = $this->record;
        $title = $course->information->where('language_id' , Language::query()->where('code' , 'en')->first()->id)->first()->title;

        Notification::make()
            ->title('New Course Created')
            ->icon('heroicon-o-video-camera')
            ->body("**{$title} has been Created.**")
            ->actions([
                Action::make('View')
                    ->url(CoursesResource::getUrl('edit', ['record' => $course]))
            ])
            ->sendToDatabase(auth()->guard('admin')->user());
    }

    private function createSlug($string)
    {
        $slug = preg_replace('/\s+/u', '-', trim($string));
        $slug = str_replace('/', '', $slug);
        $slug = str_replace('?', '', $slug);
        $slug = str_replace(',', '', $slug);

        return mb_strtolower($slug);
    }
}
