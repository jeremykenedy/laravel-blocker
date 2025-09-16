@php
    if (!isset($actionBtnIcon)) {
        $actionBtnIcon = null;
    } else {
        $actionBtnIcon = $actionBtnIcon . ' fa-fw';
    }
    if (!isset($modalClass)) {
        $modalClass = null;
    }
    if (!isset($btnSubmitText)) {
        $btnSubmitText = trans('laravelblocker::laravelblocker.modals.btnConfirm');
    }
@endphp
<div class="modal fade modal-{{$modalClass}}" id="{{$formTrigger}}" role="dialog" aria-labelledby="{{$formTrigger}}Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{$modalClass}}">
                <h5 class="modal-title">
                    Confirm
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure?
                </p>
            </div>
            <div class="modal-footer">
                {{ html()->button()
                        ->class('btn btn-outline pull-left btn-flat')
                        ->type('button')
                        ->attribute('data-dismiss', 'modal')
                        ->html('<i class="fa fa-fw fa-close" aria-hidden="true"></i> ' . trans('laravelblocker::laravelblocker.modals.btnCancel')) }}
                {{ html()->button()
                        ->class('btn btn-' . $modalClass . ' pull-right btn-flat')
                        ->type('button')
                        ->id('confirm')
                        ->html('<i class="fa ' . $actionBtnIcon . '" aria-hidden="true"></i> ' . $btnSubmitText) }}
            </div>
        </div>
    </div>
</div>
