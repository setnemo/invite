@extends('layouts.app')

@section('content')
    <?php
    $account     = json_decode(session()->get('acc', '{}'), true);
    $data        = json_decode(session()->get('codes', '{}'), true);
    $codes       = $data['codes'] ?? [];
    $handle      = $account['handle'] ?? '';
    $trains      = \App\Models\InviteCode::getCodesByHandle($handle);
    $usedCodes   = \App\Models\InviteCode::query()->withTrashed()->get()->pluck('code')->toArray();
    $canAddCodes = in_array($handle, \App\Models\InviteCode::CAN_ADD_CODES);
    $queues      = \App\Models\InviteCode::getQueuesByHandle($handle);
    $isConductor = \App\Models\InviteCode::isConductor($handle);
    $isOpened    = 0;
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="accordion" id="accordionExample">
                    @if($isConductor)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingModerateCodes">
                                <button class="accordion-button{{ !$isOpened ? '' : ' collapsed' }}" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#moderateCodes"
                                        aria-expanded="{{ !$isOpened ? 'true' : 'false' }}"
                                        aria-controls="moderateCodes">
                                    Робочі Інвайт Коди Для Роздачі
                                </button>
                            </h2>
                            <div id="moderateCodes" class="accordion-collapse collapse {{ !$isOpened++ ? 'show' : '' }}"
                                 aria-labelledby="headingModerateCodes"
                                 data-bs-parent="#moderateCodes">
                                <div class="accordion-body">
                                    @include('codes.parts.given-codes')
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingMyInviteCodes">
                            <button class="accordion-button{{ !$isOpened ? '' : ' collapsed' }}" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseMyInviteCodes"
                                    aria-expanded="{{ !$isOpened ? 'true' : 'false' }}"
                                    aria-controls="collapseMyInviteCodes">
                                Мої інвайт коди для дарування
                            </button>
                        </h2>
                        <div id="collapseMyInviteCodes"
                             class="accordion-collapse collapse {{ !$isOpened++ ? 'show' : '' }}"
                             aria-labelledby="headingMyInviteCodes"
                             data-bs-parent="#collapseMyInviteCodes">
                            <div class="accordion-body">
                                @include('codes.parts.my-codes')
                            </div>
                        </div>
                    </div>
                    @if($isConductor)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingQueues">
                                <button class="accordion-button{{ !$isOpened ? '' : ' collapsed' }}" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseQueues"
                                        aria-expanded="{{ !$isOpened ? 'true' : 'false' }}"
                                        aria-controls="collapseQueues">
                                    Живі черги
                                </button>
                            </h2>
                            <div id="collapseQueues"
                                 class="accordion-collapse collapse {{ !$isOpened++ ? 'show' : '' }}"
                                 aria-labelledby="headingQueues"
                                 data-bs-parent="#collapseQueues">
                                <div class="accordion-body">
                                    @include('codes.parts.live-queues')
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($isConductor)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAddLiveQueue">
                                <button class="accordion-button{{ !$isOpened ? '' : ' collapsed' }}" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseAddLiveQueue"
                                        aria-expanded="{{ !$isOpened ? 'true' : 'false' }}"
                                        aria-controls="collapseAddLiveQueue">
                                    Додати в живу чергу
                                </button>
                            </h2>
                            <div id="collapseAddLiveQueue"
                                 class="accordion-collapse collapse {{ !$isOpened++ ? 'show' : '' }}"
                                 aria-labelledby="headingAddLiveQueue"
                                 data-bs-parent="#collapseAddLiveQueue">
                                <div class="accordion-body">
                                    @include('codes.parts.add-live-queues')
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($canAddCodes)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAddNewInviteCodes">
                                <button class="accordion-button{{ !$isOpened ? '' : ' collapsed' }}" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseAddNewInviteCodes"
                                        aria-expanded="{{ !$isOpened ? 'true' : 'false' }}"
                                        aria-controls="collapseAddNewInviteCodes">
                                    Додати робочі інвайт коди
                                </button>
                            </h2>
                            <div id="collapseAddNewInviteCodes"
                                 class="accordion-collapse collapse {{ !$isOpened++ ? 'show' : '' }}"
                                 aria-labelledby="headingAddNewInviteCodes"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    @include('codes.parts.add-codes')
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
