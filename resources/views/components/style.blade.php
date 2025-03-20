<style>
    @foreach ($styles as $name => $properties )
        {{ $name }} {
            @foreach ($properties as $propertyName => $propertyValue )
                {{ $propertyName }}: {{ $propertyValue }};
            @endforeach
        }
    @endforeach
</style>