<div class="fields">
    @foreach($fields as $field)
        <div class="form-group field fs-18">
            <label for="field_<?=$field['id']?>"><?=$field['name']?></label>
            <input type="<?=$field['type']?>" class="form-control fs-18" id="field_<?=$field['id']?>" <?=$field['attr']?>>
        </div>
    @endforeach
</div>
