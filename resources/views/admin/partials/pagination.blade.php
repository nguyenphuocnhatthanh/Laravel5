<div class="col-md-12">

    @if($objects->lastPage() > 1)
        <ul class="pagination" id="pagination">
            @if($objects->currentPage() <= 1)
                <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
            @else
                <li><a href="<?= $objects->setPath('')->appends(Request::query())->url($objects->currentPage() - 1) ?>">&laquo;</a></li>
            @endif

            @if($objects->currentPage() - $objects->getAdj() > 1)
                <li><a href="<?= $objects->setPath('')->appends(Request::query())->url(1) ?>">1</a></li>
            @endif

            @if($objects->currentPage() - $objects->getAdj() > 2)
                <li><a href="<?= $objects->setPath('')->appends(Request::query())->url(2) ?>">2</a></li>
            @endif

            @if($objects->currentPage() - $objects->getAdj() > 3)
                <li><a href="#">...</a></li>
            @endif

            @for($i = $objects->getPageMin(); $i <= $objects->getPageMax(); $i++)
                @if($i != $objects->currentPage())
                    <li><a href="{{$objects->setPath('')->appends(Request::query())->url($i)}}">{{$i}}</a></li>
                @else
                    <li class="active"><a href="#">{{$i}} <span class="sr-only">(current)</span></a></li>
                @endif
            @endfor

            @if($objects->currentPage() + $objects->getAdj() < $objects->lastPage() - 2)
                <li><a href="#">...</a></li>
            @endif

            @if($objects->currentPage() + $objects->getAdj() < $objects->lastPage() - 1)
                <li><a href="{{$objects->setPath('')->appends(Request::query())->url($objects->lastPage() - 1)}}">{{$objects->lastPage()-1}}</a></li>
            @endif

            @if($objects->currentPage() + $objects->getAdj() < $objects->lastPage())
                <li><a href="{{$objects->setPath('')->appends(Request::query())->url($objects->lastPage())}}">{{$objects->lastPage()}}</a></li>
            @endif

            @if($objects->currentPage() < $objects->lastPage())
                <li><a href="{{$objects->setPath('')->appends(Request::query())->url($objects->currentPage()+1)}}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
            @else
                <li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
            @endif
        </ul>
    @endif
</div>