<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CardResource\Pages;
use App\Models\Card;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Group;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Group for card details
                Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('card_name')
                            ->default(fn() => CardResource::generateCardName()) // Call the generateInquiryName method
                            ->disabled() // Disable the field to prevent manual editing
                            ->required()
                            ->label('Card No.')
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('user_id')->hidden(),
                        Forms\Components\TextInput::make('owner')->disabled()
                            ->default(function (callable $get) {
                                $userId = $get('user_id');
                                if ($userId) {
                                    // Fetch the user by user_id and return the name
                                    $user = \App\Models\User::find($userId);
                                    return $user ? $user->name : null;
                                }
                                return auth()->user()->name;
                            }),
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')  // Relationship to the Customer model, assuming it has a `name` field
                            ->nullable()
                            ->searchable()  // Make the field searchable
                            ->placeholder('Select a Customer'),

                        Forms\Components\Select::make('supplier_id')
                            ->relationship('supplier', 'name')  // Relationship to the Supplier model, assuming it has a `name` field
                            ->nullable()
                            ->searchable()  // Make the field searchable
                            ->placeholder('Select a Supplier'),
                        Forms\Components\Select::make('inquiry_id')
                            ->relationship('inquiry', 'inquiry_name')  // Relationship to the Supplier model, assuming it has a `name` field
                            ->nullable()
                            ->searchable()  // Make the field searchable
                            ->placeholder(placeholder: 'Inquiry ID'),
                    ])->columns(1)->columnSpan(1),

                // Group for contact details
                Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('contact_name')->nullable(),
                        Forms\Components\TextInput::make('contact_email')
                            ->nullable()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('contact_mobile')->nullable(),
                        Forms\Components\TextInput::make('contact_home_number')->nullable(),
                        Forms\Components\TextArea::make('contact_address')->nullable(),
                    ])->columns(1)->columnSpan(1),
                Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('sales_price')
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $netCost = $get('net_cost');
                                $tax = $get('tax');
                                if ($state > 1 && $netCost > 1 && $tax > 1) {
                                    $set('margin', $state - ($netCost + $tax));
                                } else {
                                    $set('margin', 0);
                                }
                            }),

                        Forms\Components\TextInput::make('net_cost')
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $salesPrice = $get('sales_price');
                                $tax = $get('tax');
                                if ($salesPrice > 1 && $state > 1 && $tax > 1) {
                                    $set('margin', $salesPrice - ($state + $tax));
                                } else {
                                    $set('margin', 0);
                                }
                            }),

                        Forms\Components\TextInput::make('tax')
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $salesPrice = $get('sales_price');
                                $netCost = $get('net_cost');
                                if ($salesPrice > 1 && $netCost > 1 && $state > 1) {
                                    $set('margin', $salesPrice - ($netCost + $state));
                                } else {
                                    $set('margin', 0);
                                }
                            }),

                        Forms\Components\TextInput::make('margin')
                            ->numeric()
                            ->default(0)
                            ->disabled(),  // Disabled so the user cannot edit this field
                    ])
                    ->columns(1)
                    ->columnSpan(1),

                // Repeater for passengers
                TableRepeater::make('passengers')
                    ->relationship('passengers')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('ticket_1')
                            ->placeholder('3 digit')
                            ->default('000')
                            ->maxLength(3)  // Limit to 10 characters
                            ->minLength(3)
                            ->nullable(),
                        Forms\Components\TextInput::make('ticket_2')
                            ->placeholder('10 digit')
                            ->default('0000000000')
                            ->maxLength(10)  // Limit to 10 characters
                            ->minLength(10)
                            ->nullable(),
                        Flatpickr::make('issue_date')
                            ->altInput(true)
                            ->dateFormat('Y-m-d')
                            ->altFormat('d M y')
                            ->nullable(),
                        Flatpickr::make('option_date')
                            ->altInput(true)
                            ->dateFormat('Y-m-d')
                            ->altFormat('d M y')
                            ->nullable(),
                        Forms\Components\TextInput::make('pnr')
                            ->nullable(),
                    ])
                    ->columnSpanFull()
                    ->columns(2)
                    ->required(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('card_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->sortable()->searchable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->sortable()
                    ->searchable()
                    ->tooltip(function ($record) {
                        $customer = $record->customer;
                        return $customer ? "Email: {$customer->email}\nPhone: {$customer->phone}\nAddress: {$customer->address}" : 'No customer details available';
                    }),

                Tables\Columns\TextColumn::make('supplier.name')
                    ->sortable()
                    ->searchable()
                    ->tooltip(function ($record) {
                        $supplier = $record->supplier;
                        return $supplier ? "Email: {$supplier->email}\nPhone: {$supplier->phone}\nAddress: {$supplier->address}" : 'No supplier details available';
                    }),

                Tables\Columns\TextColumn::make('contact_email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('sales_price')->sortable(),
                Tables\Columns\TextColumn::make('net_cost')->sortable(),
                Tables\Columns\TextColumn::make('tax')->sortable(),
                Tables\Columns\TextColumn::make('margin')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([
                // Define any filters if necessary
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCards::route('/'),
            // You can also define create/edit pages if necessary
            // 'create' => Pages\CreateCard::route('/create'),
            // 'edit' => Pages\EditCard::route('/{record}/edit'),
        ];
    }
    public static function generateCardName(): string
    {
        $latestInquiry = Card::latest('id')->first(); // Get the latest inquiry
        $latestNumber = $latestInquiry ? (int) substr($latestInquiry->card_name, 2) : 0; // Extract the number part and increment it
        $newNumber = str_pad($latestNumber + 1, 7, '0', STR_PAD_LEFT); // Increment and pad the number with leading zeros

        return 'QT' . $newNumber; // Prefix with "QR"
    }
}
