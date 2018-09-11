@foreach ($categories as $category)

    <option value="{{$category->id or ""}}"

        @isset($article->id)
            @foreach($article->categories)

            @endforeach
        @endisset

    >
        {!! $delimiter or "" !!}{{$category->title or ""}}
    </option>

    @if (count($category->children) > 0)

        @include('admin.categories.partials.categories', [
          'categories' => $category->children,
          'delimiter'  => ' - ' . $delimiter
        ])

    @endif
@endforeach