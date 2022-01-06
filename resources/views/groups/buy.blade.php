@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

    @include('layouts.partials.navbar')

    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0">Loja de grupos</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/painel">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Comprar grupos</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-md-6 col-4 align-self-right">

                    @if($sessions->count() > 0)
                    <a href="#" class="btn btn-success text-white btn-sm float-end" onclick="showModalSend()"> <i class="fas fa-paper-plane"></i> Envio em massa</a>
                    @endif

                </div>
            </div>
        </div>

        <div class="container-fluid note-has-grid">
            <ul class="nav nav-pills p-3 bg-white mb-3 align-items-center">
                <li class="nav-item">
                    <a href="javascript:void(0)" class="
                  nav-link
                  rounded-pill
                  note-link
                  d-flex
                  align-items-center
                  justify-content-center
                  active
                  px-3 px-md-3
                  me-0 me-md-2
                " id="all-category">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list feather-sm fill-white me-0 me-md-1">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3" y2="6"></line>
                            <line x1="3" y1="12" x2="3" y2="12"></line>
                            <line x1="3" y1="18" x2="3" y2="18"></line>
                        </svg><span class="d-none d-md-block font-weight-medium">All Notes</span></a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="
                  nav-link
                  rounded-pill
                  note-link
                  d-flex
                  align-items-center
                  justify-content-center
                  px-3 px-md-3
                  me-0 me-md-2
                " id="note-business">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase feather-sm fill-white me-0 me-md-1">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg><span class="d-none d-md-block font-weight-medium">Business</span></a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="
                  nav-link
                  rounded-pill
                  note-link
                  d-flex
                  align-items-center
                  justify-content-center
                  px-3 px-md-3
                  me-0 me-md-2
                " id="note-social">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2 feather-sm fill-white me-0 me-md-1">
                            <circle cx="18" cy="5" r="3"></circle>
                            <circle cx="6" cy="12" r="3"></circle>
                            <circle cx="18" cy="19" r="3"></circle>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                        </svg><span class="d-none d-md-block font-weight-medium">Social</span></a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="
                  nav-link
                  rounded-pill
                  note-link
                  d-flex
                  align-items-center
                  justify-content-center
                  px-3 px-md-3
                  me-0 me-md-2
                " id="note-important">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star feather-sm fill-white me-0 me-md-1">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg><span class="d-none d-md-block font-weight-medium">Important</span></a>
                </li>
                <li class="nav-item ms-auto">
                    <a href="javascript:void(0)" class="
                  nav-link
                  btn-primary
                  rounded-pill
                  d-flex
                  align-items-center
                  px-3
                " id="add-notes">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file feather-sm fill-white me-0 me-md-1">
                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                            <polyline points="13 2 13 9 20 9"></polyline>
                        </svg><span class="d-none d-md-block font-weight-medium fs-3">Add Notes</span></a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="note-full-container" class="note-has-grid row">

                    <div class="col-md-4 single-note-item all-category">
                        <div class="card card-body">
                            <span class="side-stick"></span>
                            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Title">
                                Title
                            </h5>
                            <p class="note-date fs-2 text-muted">Title</p>
                            <div class="note-content">
                                <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                                    Blandit tempus porttitor aasfs. Integer posuere erat a
                                    ante venenatis.
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0)" class="link me-1"><i class="far fa-star favourite-note"></i></a>
                                <a href="javascript:void(0)" class="link text-danger ms-2"><i class="far fa-trash-alt remove-note"></i></a>
                                <div class="ms-auto">
                                    <div class="category-selector btn-group">
                                        <a class=" nav-link dropdown-toggle category-dropdown label-group p-0 " data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                            <div class="category">
                                                <div class="category-business"></div>
                                                <div class="category-social"></div>
                                                <div class="category-important"></div>
                                                <span class="more-options text-dark"><i class="icon-options-vertical"></i></span>
                                            </div>
                                        </a>
                                        <div class=" dropdown-menu dropdown-menu-right category-menu ">
                                            <a class=" note-business badge-group-item badge-business dropdown-item position-relative category-business text-success " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>Business</a>
                                            <a class=" note-social badge-group-item badge-social dropdown-item position-relative category-social text-info" href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Social</a>
                                            <a class=" note-important badge-group-item badge-important dropdown-item position-relative category-important text-danger " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i> Important</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 single-note-item all-category note-important">
                        <div class="card card-body">
                            <span class="side-stick"></span>
                            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Go for lunch">
                                Go for lunch <i class="point fas fa-circle ms-1 fs-1"></i>
                            </h5>
                            <p class="note-date fs-2 text-muted">01 April 2002</p>
                            <div class="note-content">
                                <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                                    Blandit tempus porttitor aasfs. Integer posuere erat a
                                    ante venenatis.
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0)" class="link me-1"><i class="far fa-star favourite-note"></i></a>
                                <a href="javascript:void(0)" class="link text-danger ms-2"><i class="far fa-trash-alt remove-note"></i></a>
                                <div class="ms-auto">
                                    <div class="category-selector btn-group">
                                        <a class="
                            nav-link
                            dropdown-toggle
                            category-dropdown
                            label-group
                            p-0
                          " data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                            <div class="category">
                                                <div class="category-business"></div>
                                                <div class="category-social"></div>
                                                <div class="category-important"></div>
                                                <span class="more-options text-dark"><i class="icon-options-vertical"></i></span>
                                            </div>
                                        </a>
                                        <div class="
                            dropdown-menu dropdown-menu-right
                            category-menu
                          ">
                                            <a class="
                              note-business
                              badge-group-item badge-business
                              dropdown-item
                              position-relative
                              category-business
                              text-success
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>Business</a>
                                            <a class="
                              note-social
                              badge-group-item badge-social
                              dropdown-item
                              position-relative
                              category-social
                              text-info
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Social</a>
                                            <a class="
                              note-important
                              badge-group-item badge-important
                              dropdown-item
                              position-relative
                              category-important
                              text-danger
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Important</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 single-note-item all-category note-social">
                        <div class="card card-body">
                            <span class="side-stick"></span>
                            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Meeting with Mr.Jojo">
                                Meeting with Mr.Jojo
                                <i class="point fas fa-circle ms-1 fs-1"></i>
                            </h5>
                            <p class="note-date fs-2 text-muted">19 October 2021</p>
                            <div class="note-content">
                                <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                                    Blandit tempus porttitor aasfs. Integer posuere erat a
                                    ante venenatis.
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0)" class="link me-1"><i class="far fa-star favourite-note"></i></a>
                                <a href="javascript:void(0)" class="link text-danger ms-2"><i class="far fa-trash-alt remove-note"></i></a>
                                <div class="ms-auto">
                                    <div class="category-selector btn-group">
                                        <a class="
                            nav-link
                            dropdown-toggle
                            category-dropdown
                            label-group
                            p-0
                          " data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                            <div class="category">
                                                <div class="category-business"></div>
                                                <div class="category-social"></div>
                                                <div class="category-important"></div>
                                                <span class="more-options text-dark"><i class="icon-options-vertical"></i></span>
                                            </div>
                                        </a>
                                        <div class="
                            dropdown-menu dropdown-menu-right
                            category-menu
                          ">
                                            <a class="
                              note-business
                              badge-group-item badge-business
                              dropdown-item
                              position-relative
                              category-business
                              text-success
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>Business</a>
                                            <a class="
                              note-social
                              badge-group-item badge-social
                              dropdown-item
                              position-relative
                              category-social
                              text-info
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Social</a>
                                            <a class="
                              note-important
                              badge-group-item badge-important
                              dropdown-item
                              position-relative
                              category-important
                              text-danger
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Important</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 single-note-item all-category note-business">
                        <div class="card card-body">
                            <span class="side-stick"></span>
                            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Give Review for design">
                                Give Review for design
                                <i class="point fas fa-circle ms-1 fs-1"></i>
                            </h5>
                            <p class="note-date fs-2 text-muted">02 January 2000</p>
                            <div class="note-content">
                                <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                                    Blandit tempus porttitor aasfs. Integer posuere erat a
                                    ante venenatis.
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0)" class="link me-1"><i class="far fa-star favourite-note"></i></a>
                                <a href="javascript:void(0)" class="link text-danger ms-2"><i class="far fa-trash-alt remove-note"></i></a>
                                <div class="ms-auto">
                                    <div class="category-selector btn-group">
                                        <a class="
                            nav-link
                            dropdown-toggle
                            category-dropdown
                            label-group
                            p-0
                          " data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                            <div class="category">
                                                <div class="category-business"></div>
                                                <div class="category-social"></div>
                                                <div class="category-important"></div>
                                                <span class="more-options text-dark"><i class="icon-options-vertical"></i></span>
                                            </div>
                                        </a>
                                        <div class="
                            dropdown-menu dropdown-menu-right
                            category-menu
                          ">
                                            <a class="
                              note-business
                              badge-group-item badge-business
                              dropdown-item
                              position-relative
                              category-business
                              text-success
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>Business</a>
                                            <a class="
                              note-social
                              badge-group-item badge-social
                              dropdown-item
                              position-relative
                              category-social
                              text-info
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Social</a>
                                            <a class="
                              note-important
                              badge-group-item badge-important
                              dropdown-item
                              position-relative
                              category-important
                              text-danger
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Important</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 single-note-item all-category note-social">
                        <div class="card card-body">
                            <span class="side-stick"></span>
                            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Nightout with friends">
                                Nightout with friends
                                <i class="point fas fa-circle ms-1 fs-1"></i>
                            </h5>
                            <p class="note-date fs-2 text-muted">01 August 1999</p>
                            <div class="note-content">
                                <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                                    Blandit tempus porttitor aasfs. Integer posuere erat a
                                    ante venenatis.
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0)" class="link me-1"><i class="far fa-star favourite-note"></i></a>
                                <a href="javascript:void(0)" class="link text-danger ms-2"><i class="far fa-trash-alt remove-note"></i></a>
                                <div class="ms-auto">
                                    <div class="category-selector btn-group">
                                        <a class="
                            nav-link
                            dropdown-toggle
                            category-dropdown
                            label-group
                            p-0
                          " data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                            <div class="category">
                                                <div class="category-business"></div>
                                                <div class="category-social"></div>
                                                <div class="category-important"></div>
                                                <span class="more-options text-dark"><i class="icon-options-vertical"></i></span>
                                            </div>
                                        </a>
                                        <div class="
                            dropdown-menu dropdown-menu-right
                            category-menu
                          ">
                                            <a class="
                              note-business
                              badge-group-item badge-business
                              dropdown-item
                              position-relative
                              category-business
                              text-success
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>Business</a>
                                            <a class="
                              note-social
                              badge-group-item badge-social
                              dropdown-item
                              position-relative
                              category-social
                              text-info
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Social</a>
                                            <a class="
                              note-important
                              badge-group-item badge-important
                              dropdown-item
                              position-relative
                              category-important
                              text-danger
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Important</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 single-note-item all-category note-important">
                        <div class="card card-body">
                            <span class="side-stick"></span>
                            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Launch new template">
                                Launch new template
                                <i class="point fas fa-circle ms-1 fs-1"></i>
                            </h5>
                            <p class="note-date fs-2 text-muted">21 January 2015</p>
                            <div class="note-content">
                                <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                                    Blandit tempus porttitor aasfs. Integer posuere erat a
                                    ante venenatis.
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0)" class="link me-1"><i class="far fa-star favourite-note"></i></a>
                                <a href="javascript:void(0)" class="link text-danger ms-2"><i class="far fa-trash-alt remove-note"></i></a>
                                <div class="ms-auto">
                                    <div class="category-selector btn-group">
                                        <a class="
                            nav-link
                            dropdown-toggle
                            category-dropdown
                            label-group
                            p-0
                          " data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                            <div class="category">
                                                <div class="category-business"></div>
                                                <div class="category-social"></div>
                                                <div class="category-important"></div>
                                                <span class="more-options text-dark"><i class="icon-options-vertical"></i></span>
                                            </div>
                                        </a>
                                        <div class="
                            dropdown-menu dropdown-menu-right
                            category-menu
                          ">
                                            <a class="
                              note-business
                              badge-group-item badge-business
                              dropdown-item
                              position-relative
                              category-business
                              text-success
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>Business</a>
                                            <a class="
                              note-social
                              badge-group-item badge-social
                              dropdown-item
                              position-relative
                              category-social
                              text-info
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Social</a>
                                            <a class="
                              note-important
                              badge-group-item badge-important
                              dropdown-item
                              position-relative
                              category-important
                              text-danger
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Important</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 single-note-item all-category note-social">
                        <div class="card card-body">
                            <span class="side-stick"></span>
                            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Change a Design">
                                Change a Design
                                <i class="point fas fa-circle ms-1 fs-1"></i>
                            </h5>
                            <p class="note-date fs-2 text-muted">25 December 2016</p>
                            <div class="note-content">
                                <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                                    Blandit tempus porttitor aasfs. Integer posuere erat a
                                    ante venenatis.
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0)" class="link me-1"><i class="far fa-star favourite-note"></i></a>
                                <a href="javascript:void(0)" class="link text-danger ms-2"><i class="far fa-trash-alt remove-note"></i></a>
                                <div class="ms-auto">
                                    <div class="category-selector btn-group">
                                        <a class="
                            nav-link
                            dropdown-toggle
                            category-dropdown
                            label-group
                            p-0
                          " data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                            <div class="category">
                                                <div class="category-business"></div>
                                                <div class="category-social"></div>
                                                <div class="category-important"></div>
                                                <span class="more-options text-dark"><i class="icon-options-vertical"></i></span>
                                            </div>
                                        </a>
                                        <div class="
                            dropdown-menu dropdown-menu-right
                            category-menu
                          ">
                                            <a class="
                              note-business
                              badge-group-item badge-business
                              dropdown-item
                              position-relative
                              category-business
                              text-success
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>Business</a>
                                            <a class="
                              note-social
                              badge-group-item badge-social
                              dropdown-item
                              position-relative
                              category-social
                              text-info
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Social</a>
                                            <a class="
                              note-important
                              badge-group-item badge-important
                              dropdown-item
                              position-relative
                              category-important
                              text-danger
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Important</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 single-note-item all-category note-business">
                        <div class="card card-body">
                            <span class="side-stick"></span>
                            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Give review for foods">
                                Give review for foods
                                <i class="point fas fa-circle ms-1 fs-1"></i>
                            </h5>
                            <p class="note-date fs-2 text-muted">18 December 2021</p>
                            <div class="note-content">
                                <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                                    Blandit tempus porttitor aasfs. Integer posuere erat a
                                    ante venenatis.
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0)" class="link me-1"><i class="far fa-star favourite-note"></i></a>
                                <a href="javascript:void(0)" class="link text-danger ms-2"><i class="far fa-trash-alt remove-note"></i></a>
                                <div class="ms-auto">
                                    <div class="category-selector btn-group">
                                        <a class="
                            nav-link
                            dropdown-toggle
                            category-dropdown
                            label-group
                            p-0
                          " data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                            <div class="category">
                                                <div class="category-business"></div>
                                                <div class="category-social"></div>
                                                <div class="category-important"></div>
                                                <span class="more-options text-dark"><i class="icon-options-vertical"></i></span>
                                            </div>
                                        </a>
                                        <div class="
                            dropdown-menu dropdown-menu-right
                            category-menu
                          ">
                                            <a class="
                              note-business
                              badge-group-item badge-business
                              dropdown-item
                              position-relative
                              category-business
                              text-success
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>Business</a>
                                            <a class="
                              note-social
                              badge-group-item badge-social
                              dropdown-item
                              position-relative
                              category-social
                              text-info
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Social</a>
                                            <a class="
                              note-important
                              badge-group-item badge-important
                              dropdown-item
                              position-relative
                              category-important
                              text-danger
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Important</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 single-note-item all-category note-important">
                        <div class="card card-body">
                            <span class="side-stick"></span>
                            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Give salary to employee">
                                Give salary to employee
                                <i class="point fas fa-circle ms-1 fs-1"></i>
                            </h5>
                            <p class="note-date fs-2 text-muted">15 Fabruary 2021</p>
                            <div class="note-content">
                                <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                                    Blandit tempus porttitor aasfs. Integer posuere erat a
                                    ante venenatis.
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0)" class="link me-1"><i class="far fa-star favourite-note"></i></a>
                                <a href="javascript:void(0)" class="link text-danger ms-2"><i class="far fa-trash-alt remove-note"></i></a>
                                <div class="ms-auto">
                                    <div class="category-selector btn-group">
                                        <a class="
                            nav-link
                            dropdown-toggle
                            category-dropdown
                            label-group
                            p-0
                          " data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                            <div class="category">
                                                <div class="category-business"></div>
                                                <div class="category-social"></div>
                                                <div class="category-important"></div>
                                                <span class="more-options text-dark"><i class="icon-options-vertical"></i></span>
                                            </div>
                                        </a>
                                        <div class="
                            dropdown-menu dropdown-menu-right
                            category-menu
                          ">
                                            <a class="
                              note-business
                              badge-group-item badge-business
                              dropdown-item
                              position-relative
                              category-business
                              text-success
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>Business</a>
                                            <a class="
                              note-social
                              badge-group-item badge-social
                              dropdown-item
                              position-relative
                              category-social
                              text-info
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Social</a>
                                            <a class="
                              note-important
                              badge-group-item badge-important
                              dropdown-item
                              position-relative
                              category-important
                              text-danger
                            " href="javascript:void(0);"><i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                                                Important</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Add notes -->
            <div class="modal fade" id="addnotesmodal" tabindex="-1" role="dialog" aria-labelledby="addnotesmodalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title text-white">Add Notes</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="notes-box">
                                <div class="notes-content">
                                    <form action="javascript:void(0);" id="addnotesmodalTitle">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <div class="note-title">
                                                    <label>Note Title</label>
                                                    <input type="text" id="note-has-title" class="form-control" minlength="25" placeholder="Title">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="note-description">
                                                    <label>Note Description</label>
                                                    <textarea id="note-has-description" class="form-control" minlength="60" placeholder="Description" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-bs-dismiss="modal">
                                Discard
                            </button>
                            <button id="btn-n-add" class="btn btn-info" disabled="disabled">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.partials.footer')

    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalBuscaGrupos" tabindex="-1" aria-labelledby="modalBuscaGruposLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBuscaGruposLabel">Buscar grupos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="category_id">Buscar por categoria</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="">Selecione</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <hr />

                    <div class="row ">
                        <table class="table table-striped" id="show-groups">
                            <thead>
                                <tr>
                                    <th style="width:20px;">Avatar</th>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Link</th>
                                    <th>Criado</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <!--  <button type="button" class="btn btn-primary" onclick="getCategorysGroups()">Buscar </button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalSend" tabindex="-1" aria-labelledby="modalSendLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">

                    <div class="row">
                        <input type="hidden" class="form-control" id="group_id" name="group_id" />

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="message" class="col-form-label">Digite o texto</label>
                                <textarea class="form-control" id="message" name="message" rows="18" placeholder="Digite o texto a ser enviado."></textarea>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="file" class="form-label">Arquivo anexo</label>
                                <input type="file" class="form-control" id="file" name="file" />
                            </div>
                        </div>

                        <div class="col-sm-6" id="show_tags" disabled>
                            <div class="form-group">
                                <label for="tag_id" class="form-label">Enviar para tag</label>
                                <select class="form-control" name="tag_id" disabled>
                                    <option value="">Selecione</option>
                                    @forelse ($allTags->get() as $item)
                                    <option value="{{$item->id}}"> {{ $item->name }} </option>
                                    @empty
                                    @endforelse
                                </select>

                                @if ($errors->has('name'))
                                <span class="text-danger text-left">{{ $errors->first('tag_id') }}</span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="hide">
                        <hr />
                        <table class="table table-striped" id="tableListSends" width="100%">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                    <th>Mensagem</th>
                                    <th>Status</th>
                                    <th>Enviado</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btnSend">Enviar mensagem</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="POST" action="{{ route('groups.store') }}" id="formSubmit">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateLabel">Criar grupo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-sm-6">
                                <label for="name" class="form-label">Nome do grupo</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="" required />
                            </div>

                            <div class="col-sm-6">
                                <label for="description" class="form-label">Descrição do grupo</label>
                                <input type="text" class="form-control" id="description" name="description" placeholder="" required />
                            </div>

                            <div class="col-sm-12">
                                <hr />
                            </div>

                            <div class="col-sm-12">
                                <label for="desc" class="form-label">Participantes</label>

                                <div class="row" style="padding-left:10px;max-height: 260px; overflow-y:scroll">
                                    <div class="form-check col-3">
                                        <input class="form-check-input" type="checkbox" name="all" id="all" onclick="$('input[type=checkbox]').attr('checked', true);" />
                                        <label class="form-check-label" for="all">
                                            <strong> Marcar todos </strong>
                                        </label>
                                    </div>
                                    @forelse ($contacts->get() as $contact)
                                    <div class="form-check col-3">
                                        <input class="form-check-input" type="checkbox" name="contacts[]" id="contacts{{$contact->id}}" value="{{ $contact->phone }}" />
                                        <label class="form-check-label" for="contacts{{$contact->id}}">
                                            {{ $contact->name ? $contact->name : 'Sem nome' }} ({{ $contact->phone }})
                                        </label>
                                    </div>
                                    @empty
                                    <h4> Sem contatos </h4>
                                    @endforelse
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary" onclick="$(this).html('Criando...').attr('disabled', true); setTimeout( () => $(this).html('Cadastrar').attr('disabled', false), 5000);$('#formSubmit').submit()">Cadastar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endsection
    @section('scripts')

    @php $auth_id = \Auth::user()->id @endphp
    <script>
        $(() => {

            tableDefault = $('#tableList').DataTable({
                "lengthMenu": [
                    [10, 25, 50, 100, 200, 300, -1],
                    [10, 25, 50, 100, 200, 300, "Todos"]
                ],
                "aaSorting": [
                    [7, "DESC"]
                ],
                "dom": "'B'" + "<'row'<'col-sm-6'l><'col-sm-6'f>>\
                <'row'<'col-sm-12'<'table-responsive't>r>>\
                <'row'<'col-sm-5'i><'col-sm-12'p>>",
                buttons: [{
                        text: '<i class="fas fa-sync"></i>',
                        action: function(e, dt, node, config) {
                            tableDefault.ajax.reload()
                        },
                        titleAttr: 'Atualizar tabela',
                    },
                    {
                        extend: 'selectNone',
                        className: 'hide btn-selection-none',
                        text: '<i class="fas fa-check"></i>',
                        titleAttr: 'Limpar seleção'
                    },
                    {
                        extend: '',
                        className: 'bg-info',
                        text: '<i class="fas fa-search"></i>',
                        titleAttr: 'Extrair grupos',
                        action: () => {
                            sincronizarGrupos('{{ Auth::id() }}')
                        },
                    },
                    {
                        extend: '',
                        className: 'bg-primary',
                        text: '<i class="fas fa-plus"></i>',
                        titleAttr: 'Criar grupo',
                        action: () => {
                            openModalCreate();
                        },
                    },
                    /* {
                        extend:    'selectAll',
                        className: 'btn-selection-all',
                        text:      '<i class="fas fa-check-double"></i>',
                        titleAttr: 'Selecionar todos'
                    }, */
                    {
                        text: '<i class="fas fa-trash"></i>',
                        className: 'bg-danger',
                        action: function(e, dt, node, config) {
                            deleteAllGroups()
                        },
                        titleAttr: 'Limar tudo'
                    },
                ],
                lengthChange: true,
                autoFill: true,
                select: {
                    style: 'multi'
                },
                processing: false,

                deferRender: true,
                cache: true,
                destroy: true,
                serverSide: true,
                stateSave: true,
                searchDelay: 350,
                search: {
                    "smart": true,
                    "caseInsensitive": false
                },
                ajax: '/painel/groups/datatables',
                "columnDefs": [{
                        "targets": 0,
                        "render": function(data, type, row) {
                            return `${ row?.name?.length > 3 ? row?.name : 'Sem nome'}`
                        }
                    },
                    {
                        "targets": 1,
                        "render": function(data, type, row) {
                            return `<a href="#" title="${row?.desc || 'Sem descrição'}" > ${ row?.desc?.length > 3 ? row?.desc.substring(0, 10) : 'Indisponível'} </a>`
                        }
                    },
                    {
                        "targets": 2,
                        "render": function(data, type, row) {
                            return `${ row?.tags?.name ? `<i class="fas fa-tags"></i> ${row?.tags?.name}` : ''}`
                        }
                    },
                    {
                        "targets": 3,
                        "render": function(data, type, row) {
                            return `${ row?.messages_groups?.length > 0 ? row?.messages_groups?.length : row?.messages_groups?.length }`
                        }
                    },
                    {
                        "targets": 4,
                        "render": function(data, type, row) {
                            return `${row?.size ? row?.size : 0}`
                        }
                    },
                    {
                        "targets": 5,
                        "render": function(data, type, row) {
                            return `${ row?.user?.username ?? ''}`
                        }
                    },
                    {
                        "targets": 6,
                        "render": function(data, type, row) {
                            return `${ moment().diff(row?.created_at, 'days') < 1 ? `${ moment(row?.created_at).format('DD/MM/YYYY HH:mm') } <span class="badge bg-success" title="Importado recente">N</span>` : ''}`
                        }
                    },
                    {
                        "targets": 7,
                        "render": (data, type, row) => {

                            let modalSend = `<a class="btn btn-success btn-sm" href="#" onclick="openModalSend('${row?.group_id}', ${row?.user_id})"> <i class="far fa-paper-plane"></i></a>`
                            let btnEdit = `@can('grupos-editar')<a href="/painel/groups/${row?.id}/edit" class="btn btn-info btn-sm"> <i class="fas fa-edit"></i> </a>@endcan`
                            let btnDelete = `@can('grupos-deletar')<a class="btn btn-sm btn-danger" onclick="dropItem(${row?.id})" > <i class="fas fa-trash-alt"></i> </a>@endcan`

                            return `${modalSend} ${btnEdit} ${btnDelete}`;

                        }
                    }
                ],
                'language': {
                    "lengthMenu": "_MENU_ p/ página",
                    "search": "Buscar: ",
                    "loadingRecords": "Aguarde, carregando...",
                    "processing": "Aguarde, carregando...",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ dados",
                    "paginate": {
                        "first": "Primeiro",
                        "last": "Último",
                        "next": "Próximo",
                        "previous": "Anterior"
                    },
                    "zeroRecords": "Opa, nenhuma informação foi localizado.",
                },
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
            });

            $(".table-responsive #tableList_wrapper .dt-buttons button").css('margin-right', '5px')

            tableListSends = $('#tableListSends').DataTable({
                'paging': false,
                'select': true,
                'responsive': true,
                "order": [
                    [4, "desc"]
                ],
            });

            /*  table = $('#tableList').DataTable({
                 'paging': false,
                 'select': true,
                 'responsive': true,
                 "order": [
                     [6, "desc"]
                 ],
             }); */

            $('#tableList tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');

                var pos = tableDefault.row(this).index();
                var row = tableDefault.row(pos).data();

            });

            $('#modalSend').on('show.bs.modal', function(e) {

                $('#modalSend').modal({
                    backdrop: 'static'
                });

            }).on('hide.bs.modal', function(e) {

                $('.hide').css('display', 'block');

                $("select[name='tag_id']").val('');
                $("select[name='tag_id']").attr('disabled', true);

                tableDefault.ajax.reload()

            })

            file64 = ""
            $("#file").on('change', async function(e) {

                let reader = new FileReader();
                let file = await $("#file")[0].files[0]

                await reader.readAsDataURL(file);
                reader.onload = () => {
                    file64 = reader.result;
                };
            })

            $("#category_id").on('change', async function(e) {

                category_id = $("#category_id option:selected").val();

                await $.post({
                    url: `https://api.linkgrupos.app/partner/group/${category_id}/list`,
                    method: `GET`,
                    headers: {
                        'token': ''
                    },
                    beforeSend: function(data) {
                        $.LoadingOverlay('show');
                    },
                    success: function(data) {

                        data?.groups?.forEach(group => {

                            tableGroups = $("#show-groups").DataTable().row.add([
                                group?.avatar ? `<img src="${group?.avatar}" style="width: 75%"/>` : '-',
                                group?.group ?? `${group?.name}`,
                                group?.description ? `${group?.description.substr(0, 10)}...` : '-',
                                group?.url ? `<a href="#" onclick="entrarGrupo('${group?.url}')"> Entrar no grupo </a>` : 'Sem link',
                                group?.created ? `${ moment(group?.created).format('DD/MM/Y HH:MM') }` : '-',
                            ]).draw(false);

                        })

                        $.LoadingOverlay('hide');

                    },
                    error: function(error) {

                        $.LoadingOverlay('hide');
                        Swal.fire({
                            class: 'error',
                            icon: 'error',
                            title: `Não importado`,
                            text: `Erro ao obter informações.`,
                        })

                    },
                });


            })

        })

        async function entrarGrupo(url) {

            Swal.fire('Em breve', 'Aguarde alguns dias para esta funcionalidade.', 'info')

        }

        async function showModalSend() {

            $('#modalSend').modal('show');
            $("select[name='tag_id']").attr('disabled', false);
            $("#btnSend").attr('onclick', `sendText('{{$auth_id}}', false)`);

            $('.hide').css('display', 'none');

        }

        async function openModalCreate() {
            $('#modalCreate').modal('show');
        }

        async function openModalSend(group_id, user_id) {

            console.log(group_id, user_id)

            $("#group_id").val(group_id);
            $("#btnSend").attr('onclick', `sendText('${user_id}', false)`);

            await $.post({
                url: `/painel/groups/${group_id}/show`,
                method: `POST`,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "group_id": `${group_id}`,
                },
                beforeSend: function(data) {
                    $.LoadingOverlay('show');
                },
                success: function(data) {

                    $("#tableListSends").DataTable().clear();

                    data?.group?.messages_light?.forEach((data) => {

                        tableListSends = $("#tableListSends").DataTable().row.add([
                            data?.name ? `${data?.name.substr(0, 10)}...` : '-',
                            data?.number ? `Grupo` : `${data?.number}`,
                            data?.message ? `${data?.message.substr(0, 10)}...` : '-',
                            data?.status ? `${data?.status}` : '-',
                            data?.created_at ? `${ moment(data?.created_at).format('DD/MM/Y HH:MM') }` : '-',
                        ]).draw(false);
                    })

                    $(".modal-title").html(`Enviar mensagem`);
                    $('#modalSend').modal('show');

                    $.LoadingOverlay('hide');

                },
                error: function(error) {

                    $.LoadingOverlay('hide');
                    Swal.fire({
                        class: 'error',
                        icon: 'error',
                        title: `Não importado`,
                        text: `Erro ao obter informações.`,
                    })

                },
            });
        }

        async function openModalGetGroups() {

            await $.post({
                url: `https://api.linkgrupos.app/partner/category/list`,
                method: `GET`,
                headers: {
                    'token': '7v7vtk2emapggc90cgm0yhgoy'
                },
                beforeSend: function(data) {
                    $.LoadingOverlay('show');
                },
                success: function(data) {

                    if (data?.length > 0) {

                        data.forEach((data) => {

                            $("#category_id").append(`<option value="${data.categoryId}">${data.categoryName}</option>`);

                        })

                    }

                    $.LoadingOverlay('hide');
                    $("#modalBuscaGrupos").modal('show');

                },
                error: function(error) {

                    $.LoadingOverlay('hide');
                    Swal.fire({
                        class: 'error',
                        icon: 'error',
                        title: `Não importado`,
                        text: `${ error?.responseJSON?.message || 'Erro ao obter categorias, verifique se a sua sessão está conectada.' }`,
                    })

                },
            });

        }

        async function sincronizarGrupos(user_id) {

            await $.post({
                url: `/painel/groups/get`,
                method: `POST`,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "user_id": `${user_id}`,
                },
                beforeSend: function(data) {
                    $.LoadingOverlay('show');
                },
                success: function(data) {

                    if (data?.groups?.length > 0) {
                        window.location.reload()
                    }

                    $.LoadingOverlay('hide');
                },
                error: function(error) {

                    $.LoadingOverlay('hide');
                    Swal.fire({
                        class: 'error',
                        icon: 'error',
                        title: `Não importado`,
                        text: `${ error?.responseJSON?.message || 'Erro ao obter grupos, verifique se a sua sessão está conectada.' }`,
                    })

                },
            });

        }

        async function dropItem(id) {

            $.confirm({
                title: 'Confirmação',
                content: 'Deseja mesmo deletar esse grupo?',
                dragWindowBorder: true,
                animationBounce: 1.5,
                theme: 'modern',
                buttons: {
                    Cancelar: {
                        btnClass: 'btn-green',
                        keys: ['enter', 'space'],
                        action: function() {}
                    },
                    Deletar: {
                        btnClass: 'btn-red',
                        keys: ['enter', 'space'],
                        action: function() {

                            $.LoadingOverlay('show');

                            $.post({
                                url: `/painel/groups/${id}/delete`,
                                method: "delete",
                                data: {
                                    '_token': $('meta[name="csrf-token"]').attr('content'),
                                },
                                success: function(retorno) {
                                    if (retorno != 'undefined') {
                                        tableDefault.ajax.reload();

                                    };

                                    $.LoadingOverlay('hide');

                                },
                                error: function(error) {
                                    $.LoadingOverlay("hide");

                                },
                            });
                        }
                    }
                }
            });

        }

        async function deleteAllGroups(user_id) {

            Swal.fire({
                title: 'Deseja mesmo deletar todos os grupos?',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Sim',
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post({
                        url: `/painel/groups/deleteAll`,
                        method: `DELETE`,
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function(data) {
                            $.LoadingOverlay('show');
                        },
                        success: function(data) {

                            $.LoadingOverlay('hide');
                            Swal.fire('Limpo!', '', 'success')

                            tableDefault.ajax.reload();

                        },
                        error: function(error) {

                            $.LoadingOverlay('hide');
                            Swal.fire({
                                class: 'error',
                                icon: 'error',
                                title: `Ops!`,
                                text: `Erro ao deletar grupos.`,
                            })

                        },
                    });

                } else if (result.isDenied) {

                    Swal.fire('Sem alterações', '', 'info')
                }

            })

        }
    </script>
    @endsection
