<?php

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * The active categories to display.
     *
     * @var Collection<int, Category>
     */
    public Collection $categories;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->categories = Category::query()
            ->with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();
    }
}; ?>

<div>
    <div class="settings-heading">
        <flux:heading size="xl" level="1">Categorías</flux:heading>
        <flux:subheading size="lg" class="mb-6">Catálogo de categorías del producto</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if ($categories->isEmpty())
        <div class="card text-center text-sm text-muted">
            No hay categorías registradas.
        </div>
    @else
        <div class="settings-grid">
            @foreach ($categories as $category)
                <div class="card">
                    <div class="form-actions form-actions--between">
                        <h2 class="text-strong">{{ $category->name }}</h2>
                        <span class="text-xs text-muted">
                            {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>

                    @if ($category->description)
                        <p class="text-sm text-muted">{{ $category->description }}</p>
                    @endif

                    @if ($category->children->isNotEmpty())
                        <ul>
                            @foreach ($category->children as $child)
                                <li class="form-actions form-actions--between text-sm">
                                    <span>{{ $child->name }}</span>
                                    <span class="text-xs text-muted">{{ $child->is_active ? 'Visible' : 'Oculta' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>