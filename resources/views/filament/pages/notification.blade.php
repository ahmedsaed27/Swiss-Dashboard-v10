<x-filament-panels::page>
    <form wire:submit.prevent="create" class="space-y-4">
        {{ $this->form }}
        <div class="flex gap-2">
            <x-filament::button type="submit">Create</x-filament::button>
            <x-filament::button type="button" color="gray" wire:click="cancel">Cancel</x-filament::button>
        </div>
        
    </form>
</x-filament-panels::page>
