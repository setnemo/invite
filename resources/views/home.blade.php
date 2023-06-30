@extends('layouts.app')

@section('content')
    <?php
    $account               = json_decode(session()->get('account', '{}'), true);
    $data                  = json_decode(session()->get('codes', '{}'), true);
    $codes                 = $data['codes'] ?? [];
    $handle                = $account['handle'] ?? '';
    $addedCodes            = \App\Models\InviteCode::query()->get()->pluck('code')->toArray();
    $deletedCodes          = \App\Models\InviteCode::query()->withTrashed()->get()->pluck('code')->toArray();
    $trains                = \App\Models\InviteCode::getCodesByHandle($handle);
    $isSuperAdmin          = in_array($handle, \App\Models\InviteCode::SUPER_ADMINS);
    $queues                = \App\Models\InviteCode::getQueuesByHandle($handle);
    $isConductor           = \App\Models\InviteCode::isConductor($handle);
    $isOpened              = 0;
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="accordion" id="accordionCodes">
                    @if($isConductor)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingModerateCodes">
                                <button class="accordion-button{{ !$isOpened ? '' : ' collapsed' }}" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#moderateCodes"
                                        aria-expanded="{{ !$isOpened ? 'true' : 'false' }}"
                                        aria-controls="moderateCodes">
                                    Доступні інвайт-коди 🎟️ для роздачі
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
                                Мої інвайт-коди 🎟️
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
                                    Живі черги 🚶🚶🚶
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
                                    Додати в живу чергу 🚶🚶🚶➕🚶
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
                    @if($isSuperAdmin)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAddNewInviteCodes">
                                <button class="accordion-button{{ !$isOpened ? '' : ' collapsed' }}" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseAddNewInviteCodes"
                                        aria-expanded="{{ !$isOpened ? 'true' : 'false' }}"
                                        aria-controls="collapseAddNewInviteCodes">
                                    Додати інвайт-код 🎟️ вручну
                                </button>
                            </h2>
                            <div id="collapseAddNewInviteCodes"
                                 class="accordion-collapse collapse {{ !$isOpened++ ? 'show' : '' }}"
                                 aria-labelledby="headingAddNewInviteCodes"
                                 data-bs-parent="#accordionAddNewInviteCodes">
                                <div class="accordion-body">
                                    @include('codes.parts.add-codes')
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($isSuperAdmin)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingMoveChurchCodesTo">
                                <button class="accordion-button{{ !$isOpened ? '' : ' collapsed' }}" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseMoveChurchCodesTo"
                                        aria-expanded="{{ !$isOpened ? 'true' : 'false' }}"
                                        aria-controls="collapseMoveChurchCodesTo">
                                    Перекинути інвайт-код 🎟️ в інший потяг
                                </button>
                            </h2>
                            <div id="collapseMoveChurchCodesTo"
                                 class="accordion-collapse collapse {{ !$isOpened++ ? 'show' : '' }}"
                                 aria-labelledby="headingMoveChurchCodesTo"
                                 data-bs-parent="#accordionMoveChurchCodesTo">
                                <div class="accordion-body">
                                    @include('codes.parts.move-codes')
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($isSuperAdmin)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingInviteCodeRestoring">
                                <button class="accordion-button{{ !$isOpened ? '' : ' collapsed' }}" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseInviteCodeRestoring"
                                        aria-expanded="{{ !$isOpened ? 'true' : 'false' }}"
                                        aria-controls="collapseInviteCodeRestoring">
                                    Відновити інвайт-коди 🎟️
                                </button>
                            </h2>
                            <div id="collapseInviteCodeRestoring"
                                 class="accordion-collapse collapse {{ !$isOpened++ ? 'show' : '' }}"
                                 aria-labelledby="headingInviteCodeRestoring"
                                 data-bs-parent="#accordionInviteCodeRestoring">
                                <div class="accordion-body">
                                    @include('codes.parts.restore-codes')
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingInviteRestoring">
                                <button class="accordion-button{{ !$isOpened ? '' : ' collapsed' }}" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseInviteRestoring"
                                        aria-expanded="{{ !$isOpened ? 'true' : 'false' }}"
                                        aria-controls="collapseInviteRestoring">
                                    Відновити чергу 🚶🚶🚶
                                </button>
                            </h2>
                            <div id="collapseInviteRestoring"
                                 class="accordion-collapse collapse {{ !$isOpened++ ? 'show' : '' }}"
                                 aria-labelledby="headingInviteRestoring"
                                 data-bs-parent="#accordionInviteRestoring">
                                <div class="accordion-body">
                                    @include('codes.parts.restore-invites')
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
