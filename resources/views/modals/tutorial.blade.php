<div class="modal fade" id="tutorial" tabindex="-1" role="dialog" aria-labelledby="tutorialLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="tutorialLabel">Tutorial</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                <li data-target="#myCarousel" data-slide-to="1"></li>
                                <li data-target="#myCarousel" data-slide-to="2"></li>
                                <li data-target="#myCarousel" data-slide-to="3"></li>
                            </ol>

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner" role="listbox">
                                <div class="item active">
                                    <img src="img/tutorial/slide-1.png" alt="Create a team">
                                </div>

                                <div class="item">
                                    <img src="img/tutorial/slide-2.png" alt="Chania">
                                </div>

                                <div class="item">
                                    <img src="img/tutorial/slide-3.png" alt="Flower">
                                </div>

                                <div class="item">
                                    <img src="img/tutorial/slide-4.png" alt="Flower">
                                </div>
                            </div>

                            <!-- Left and right controls -->
                            <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form id="hide-tutorial" action="{{ route('ajax.tutorial.hide') }}" method="POST">
                    {{  csrf_field() }}
                    <div class="checkbox">
                        <input id="hide" type="checkbox" name="hide" value="1">
                        <label for="hide" class="pull-left"> Don't show me this again</label>
                    </div>
                </form>
                <button type="submit" class="btn btn-primary" form="hide-tutorial">Close</button>
            </div>
        </div>
    </div>
</div>