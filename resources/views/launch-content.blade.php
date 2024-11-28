<div class="card-body card-body-height">
    <div class="row">
        <div class="col-sm-4">
            <img src="{{ asset('images/list.svg') }}" width="20px" alt="list" />
            &nbsp; Records : {{$totalRecors}}
        </div>
        <div class="col-sm-5 card-body-div">
            <div class="count-checkboxs-wrapper div-clear-section" id="non_selected_div">
                <span class="count-records-section">&#10003;</span>
                <span id="count-checked-checkboxs">
                    <small>0</small>
                </span>
                <small>Selected</small>
                <a href="#" class="text-decoration-none" id="selected_view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <small>&nbsp; View</small>
                </a>
            </div>
        </div>
            
        <div class="col-sm-3 card-body-div">
            <button type="button" id="addLink" class="btn btn-primary" value="{{ $requestId }}" disabled>
                Add as Link
            </button>
        </div>
    </div>
    <br />

   
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-align">
            @if(!empty($bookId))
                <li class="breadcrumb-item"><a href='javascript:;' id="back" onclick='back_menu(this.id);' title="Home">Home</a></li>
                @if($sectionType == '')
                    <li class="breadcrumb-item">{{$title ? $title : $source}}</li>
                @else
                    <li class="breadcrumb-item">
                        <a href='javascript:void(0);' id="{{$bookId}}" title="{{$oldTitle}}"
                            data-book_id="{{$bookId}}"
                            data-content_type=""
                            data-section_type=""
                            data-chapter_id=""
                            data-request_id="{{$requestId}}"
                            data-title="{{$oldTitle}}"
                            onclick="fetch_details_object(this.dataset.content_type, this.dataset.book_id, this.dataset.chapter_id, this.dataset.section_type, this.title);"
                            class="center">
                            {{$source}}
                        </a>
                        </li>
                        <li class="breadcrumb-item">
                            {{$title}}
                        </li>
                @endif
            @endif
        </ol>
    </nav>

    <input type="hidden" value="{{$bookId}}" id="hidden_Book_id" name="hidden_Book_id">
    <input type="hidden" value="{{$title}}" id="hidden_Book_title" name="hidden_Book_title">
    <input type="hidden" value="{{$oldBookId}}" id="hidden_old_book_id" name="hidden_old_book_id">
    <input type="hidden" value="{{$oldTitle}}" id="hidden_old_book_title" name="hidden_old_book_title">

    <input type="hidden" value="{{$flTopLevelContentDisplayName}}" id="hidden_flTopLevelContentDisplayName" name="hidden-flTopLevelContentDisplayName">
    <input type="hidden" value="{{$flSiteID}}" id="hidden_flSiteID" name="hidden-flSiteID">
    <input type="hidden" value="{{$contentType}}" id="hidden_content_type" name="hidden_content_type">
    <input type="hidden" value="{{$sectionType}}" id="hidden_section_type" name="hidden_section_type">
    <input type="hidden" value="{{$chapterId}}" id="hidden_chapter_id" name="hidden_chapter_id">

    @if($totalRecors > 0)
    <div class="content-load-margin">
        @if(!empty($responseArr))
    @foreach ($responseArr as $assets)

    <?php
        $displayNoneClass = '';

        // default non-clickable
        $disableLink = 'inactiveLink';

        $bookBased = ' bookBased';
        $chapterBased = ' chapterBased';

        if( // for level 1 clickable
            empty($assets['SectionType'])
            && (
                (!empty($assets['BookId']) && in_array($assets['ContentType'], $contentTypes['l1']))
                || (!empty($assets['ChapterId']) && in_array($assets['ContentType'], $contentTypes['l1']))
            )
        ){
            $disableLink = 'activeLink level-1' . ( !empty($assets['BookId']) ? $bookBased : '' ) . ( !empty($assets['ChapterId']) ? $chapterBased : '' );
        }elseif( // for level 2 clickable
            !empty($assets['ChapterId']) && in_array($assets['ContentType'], $contentTypes['l2']) && $assets['SectionType'] == 'Chapter'
        ){
            $disableLink = 'activeLink  level-2' . ( !empty($assets['BookId']) ? $bookBased : '' ) . ( !empty($assets['ChapterId']) ? $chapterBased : '' );
        }elseif( // for level 3 clickable
            !empty($assets['ChapterId']) && in_array($assets['ContentType'], $contentTypes['l3']) && $assets['SectionType'] == 'Chapter'
        ){
            $disableLink = 'activeLink  level-3' . ( !empty($assets['BookId']) ? $bookBased : '' ) . ( !empty($assets['ChapterId']) ? $chapterBased : '' );
        }else{
            $disableLink = 'inactiveLink';
        }

        $contentTypeData = str_replace(' ', '', $assets['ContentType']);
        
        if(!empty($assets['WebUrls']))
        {
            $short_url=md5($assets['WebUrls'][0]);
        } else {
            $assets['WebUrls'][0] = '';
            $short_url=md5('');
        }
    ?>
    <span class="galleryImage float-center" style="{{$displayNoneClass}}">
        
        <div class="gallery">
            <div class="">
                
                <a href='javascript:void(0);' id="{{$assets['BookId']}}" title="{{$assets['Title']}}"
                    data-book_id="{{$assets['BookId']}}"
                    data-content_type="{{$assets['ContentType']}}"
                    data-section_type="{{$assets['SectionType']}}"
                    data-chapter_id="{{$assets['ChapterId']}}"
                    data-request_id="{{$requestId}}"
                    data-title="{{$assets['Title']}}"
                    onclick="fetch_details_object(this.dataset.content_type, this.dataset.book_id, this.dataset.chapter_id, this.dataset.section_type, this.title);"
                    class="center {{$disableLink}}">
                        <img src="{{$assets['CoverImageUrl']}}" onerror="this.src='/images/not-found-image.png'" alt="" class="asset-image"/>
                        <div class="overlay asset-overlay">
                            <p class="asset-overlay-content"></p>
                        </div>
                </a>
                <div class="desc ellipsis">
                    <span title="{{$assets['Title']}}">{{$assets['Title']}} </span>
                    <div style="display:none;">
                    <!-- To Test  ContentType/SectionType/ChapterId/BookId-->
                        <br/><span>CT- {{$assets['ContentType']}}</span>
                        <br/><span>ST- {{$assets['SectionType']}}</span>
                        <br/><span>CId- {{$assets['ChapterId']}}</span>
                        <br/><span>BId- {{$assets['BookId']}}</span>
                    </div>
                </div>
                
                <input type="checkbox" name="assetcheckbox"  id="assetcheckbox_{{$assets['BookId']}}_{{$contentTypeData}}_{{$assets['SectionType']}}_{{$assets['ChapterId']}}_{{$short_url}}" data-id="{{$assets['BookId']}}" data-cover_image_url="{{$assets['CoverImageUrl']}}" data-type="link" data-content_type="{{$assets['ContentType']}}" data-section_type="{{$assets['SectionType']}}" data-chapter_id="{{$assets['ChapterId']}}" data-title="{{ $assets['Title'] }}" data-url="{{ $assets['WebUrls'][0] }}" data-thumbnail="{{ $assets['CoverImageUrl'] }}" />
                
                <a href="{{$assets['WebUrls'][0]}}" target="_blank">
                    <button title="{{$assets['Title']}}" class="preview-button" value="preview">
                    </button>
                </a>
            </div>
        </div>
    </span>
  @endforeach
  @endif

  <br />
  <br />

<div class="div-pagination">
  <nav aria-label="Page navigation example">
    <ul class="pagination flex-wrap">
        <?php
            if ($page <= 1) {
                $selectedPrevious = 'disabled';
            } else {
                $selectedPrevious = '';
            }
        ?>
        <li class="page-item <?php echo ($page <= 10) ? 'disabled' : '' ?>">
            <?php
                $pagesBack = $page - 10;
                ?>
            <a class="page-link" href='javascript:;' id="{{$pagesBack}}" onclick='paginate(this.id);' title='-10 pages'> << </a>
        </li>
        
        <li class="page-item {{$selectedPrevious}}">
            <a class="page-link" href='javascript:;' id="{{$page - 1}}" onclick='paginate(this.id);'>Previous</a>
        </li>
        
        @if($page >= 1)
            <?php 
            if($page == 1) {
                $activeClass = 'active';
            } else {
                $activeClass = '';
            }
            ?>
            <li class="page-item">
                <a class="page-link {{$activeClass}}" href='javascript:;' id="1" onclick='paginate(this.id);'>1</a>
            </li>

            @if($page > 10)
                <?php $selectedChild = 'disabled'; ?>
                <li class="page-item {{$selectedChild}}">
                    <a class="page-link" href='javascript:;' id="{{$totalPages}}" {{$selectedPrevious}}>...</a>
                </li>
            @endif
        @endif

        @for($i=0; $i<=9; $i++)
            <li class="page-item">
                <?php
                    $offset = 1;
                    if((($totalPages - $page) < 9)) {
                        $offset = $totalPages - 9;
                    } elseif($page > 10 && (($totalPages - $page) >= 9) ) {
                        $offset = $page;
                    }
                    if ($page == ($offset + $i)) {
                        $selectedChild = 'active';
                    } else {
                        $selectedChild = '';
                    }
                ?>
                @if(($offset + $i) > 1)
                    <a class="page-link {{$selectedChild}}" href='javascript:;' id="{{$offset + $i}}" onclick='paginate(this.id);'>{{$offset + $i}}</a>
                @endif
            </li>
        @endfor
        
        @if($totalPages != ($page) && ($totalPages - $page) >= 10)
            @if(($totalPages - $page) > 10)
                <li class="page-item disabled">
                    <a class="page-link" href='javascript:;' id="{{$totalPages}}">...</a>
                </li>
            @endif
            <li class="page-item">
                <a class="page-link" href='javascript:;' id="{{$totalPages}}" onclick='paginate(this.id);'>{{$totalPages}}</a>
            </li>
        @endif

        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href='javascript:;' id="{{$page + 1}}" onclick='paginate(this.id);'>Next</a>
        </li>
        
        <li class="page-item <?php echo (($totalPages - 9) <= $page) ? 'disabled' : '' ?>">
            <?php
                $pagesBack = $page + 10;
            ?>
            <a class="page-link" href='javascript:;' id="{{$pagesBack}}" onclick='paginate(this.id);' title='+10 pages'> >> </a>
        </li>
    </ul>
  </nav>
</div>
  
    </div>
  @else
    <div class="div-no-record">
        <span>No records found</span>
    </div>
  @endif
</div>


  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <div class="col-9">
                <span class="modal-title" id="exampleModalLabel">Selected Items</span>
            </div>
            <div class="col-2">
                <a href="#" id="UncheckAll" value="UncheckAll" class="count-clear-section text-danger float-right">
                    <small>  Clear All</small>
                </a>
            </div>
            <div class="col-1">
                <button type="button" class="close float-right model-cloase-border" data-bs-dismiss="modal" aria-label="Close">
                    <h3>×</h3>
                </button>
            </div>
      </div>

        <div class="container display-block">
            <div class='modal-body' class="scroll-bar">
          ...
            </div>
        </div>
      </div>
    </div>
  </div>
<!-- Scripts -->
<script src="{{ asset('js/launchcontent.js')}}"></script>

