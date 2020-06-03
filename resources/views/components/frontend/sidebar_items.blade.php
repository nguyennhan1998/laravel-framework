{{--<div class="sidebar_item">--}}
{{--    <h4>Department</h4>--}}
{{--    <ul>--}}
{{--        @foreach($categories as $category)--}}
{{--            <li><a href="{{$cat->getCategoryUrl()}}"></a></li>--}}
{{--        @endforeach--}}
{{--    </ul>--}}
{{--</div>--}}

<div class="sidebar__item">
    <ul>
        @foreach(\App\Category::all() as $cat)
            <li><a href="{{$cat->getCategoryUrl()}}">{{$cat->__get("category_name")}}</a></li>
        @endforeach
    </ul>
</div>
