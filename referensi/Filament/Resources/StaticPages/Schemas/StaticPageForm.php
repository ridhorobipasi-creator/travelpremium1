<?php

namespace App\Filament\Resources\StaticPages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StaticPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('content_type')
                    ->label('Tipe Konten')
                    ->options([
                        'text' => 'Teks Standar (Rich Editor)',
                        'html' => 'Kode HTML Kustom (Bisa berisi CSS/JS dll)'
                    ])
                    ->default('text')
                    ->reactive()
                    ->required()
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('Konten Teks')
                    ->hidden(fn ($get) => $get('content_type') === 'html')
                    ->columnSpanFull(),
                Textarea::make('html_content')
                    ->label('Konten HTML')
                    ->placeholder('Masukkan kode HTML lengkap, termasuk tag <style> atau <script> jika diperlukan...')
                    ->rows(20)
                    ->hidden(fn ($get) => $get('content_type') !== 'html')
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->default(false),
                Section::make('SEO')
                    ->schema([
                        TextInput::make('meta_title')
                            ->maxLength(255),
                        Textarea::make('meta_description')
                            ->rows(2)
                            ->maxLength(500),
                    ])
                    ->collapsed(),
            ]);
    }
}
