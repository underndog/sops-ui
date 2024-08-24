$(document).ready(function(){
    $('#add-new-key').click(function(){
        $('#dynamic_field').append(`
            <div class="row mt-4 dynamic-row">
                <div class="col-12 col-sm-3">
                    <div class="input-group input-group-static">
                        <label>Key</label>
                        <input name="keys[]" type="text" class="form-control multisteps-form__input">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="input-group input-group-static">
                        <label>Value</label>
                        <textarea name="values[]" class="form-control multisteps-form__input"></textarea>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <button type="button" class="btn btn-danger btn-remove-row remove-secret-key">Remove</button>
                </div>
            </div>
        `);
    });
    $(document).on('click', '.remove-secret-key', function(){
        $(this).closest('.dynamic-row').remove();
    });
});