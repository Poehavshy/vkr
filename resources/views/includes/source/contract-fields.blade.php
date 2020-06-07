<div id="fields" class="fields">
    @foreach($fields as $field)
        <div class="form-group field fs-18">
            <label for="field_<?=$field['id']?>"><?=$field['name']?></label>
            <input type="<?=$field['type']?>" name="<?=$field['name']?>" data-purpose="<?=$field['purpose']?>" class="form-control fs-18" id="field_<?=$field['id']?>" <?=$field['attr']?> required>
        </div>
    @endforeach
</div>
