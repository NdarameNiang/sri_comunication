@if($section->type === 'list')
    @php $items = $section->content_json ?? []; @endphp
    <div class="grid grid-cols-1 {{ count($items) > 4 ? 'sm:grid-cols-2' : '' }} gap-3.5">
        @foreach($items as $item)
        <div class="flex items-start gap-2.5">
            <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></div>
            <p class="text-sm text-slate-600 leading-relaxed">
                @if(!empty($item['title']))
                    <span class="font-semibold text-slate-800">{{ $item['title'] }}</span>
                    @if(!empty($item['description'])) — @endif
                @endif
                {{ $item['description'] ?? '' }}
            </p>
        </div>
        @endforeach
    </div>
@else
    <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $section->content }}</p>
@endif
