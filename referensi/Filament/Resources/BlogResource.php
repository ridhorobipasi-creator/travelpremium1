<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Schemas;
use Filament\Resources\Resource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten Website';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Artikel / Blog';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Informasi Dasar')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Banner Artikel / Slider')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->disk('public')
                            ->directory('blog')
                            ->visibility('public')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('excerpt')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Schemas\Components\Section::make('Status & SEO')
                    ->collapsed()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Toggle::make('is_published')->default(false),
                        Forms\Components\DateTimePicker::make('published_at'),
                        Forms\Components\TextInput::make('view_count')->numeric()->default(0),
                        Forms\Components\TextInput::make('meta_title')->maxLength(255)->columnSpanFull(),
                        Forms\Components\Textarea::make('meta_description')->rows(2)->columnSpanFull(),
                    ]),

                // ─────────────────────────────────────────────────────────────
                // TERJEMAHAN (Bahasa Asing)
                // ─────────────────────────────────────────────────────────────
                Schemas\Components\Section::make('Terjemahan (Bahasa Asing)')
                    ->description('Isi konten dalam bahasa Inggris dan Melayu.')
                    ->collapsed()
                    ->schema([
                        Schemas\Components\Tabs::make('Translations')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English (EN)')
                                    ->schema([
                                        Forms\Components\TextInput::make('translations.en.title')->label('Judul Artikel (EN)'),
                                        Forms\Components\Textarea::make('translations.en.excerpt')->label('Kutipan Singkat (EN)'),
                                        Forms\Components\RichEditor::make('translations.en.content')->label('Isi Artikel (EN)'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('Malaysia (MS)')
                                    ->schema([
                                        Forms\Components\TextInput::make('translations.ms.title')->label('Judul Artikel (MS)'),
                                        Forms\Components\Textarea::make('translations.ms.excerpt')->label('Kutipan Singkat (MS)'),
                                        Forms\Components\RichEditor::make('translations.ms.content')->label('Isi Artikel (MS)'),
                                    ]),
                            ])->columnSpanFull()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->limit(50),
                Tables\Columns\IconColumn::make('is_published')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('view_count')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->since()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published'),
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\CreateAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('published_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
