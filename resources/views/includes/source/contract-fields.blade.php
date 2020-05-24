<div class="fields">
    <?php
    foreach ($fields as $field){
            ?>
            <div class="form-group field fs-18">
                <label for="field_<?=$field['id']?>"><?=$field['name']?></label>
                <?php
                    if ($field['type'] === "string"){
                        ?>
                    <input type="text" class="form-control fs-18" id="field_<?=$field['id']?>" placeholder="" required>
                <?php
                    }
                    else if ($field['type'] === "int"){
                        ?>
                        <input type="number" class="form-control fs-18" id="field_<?=$field['id']?>" placeholder="" min="0" max="90" required>
                <?php
                    }
                    else if ($field['type'] === "float"){
                        ?>
                <input type="number" class="form-control fs-18" id="field_<?=$field['id']?>" placeholder="" min="0.000001" max="1000" step="0.000001" required>
            <?php
                    }
                ?>
            </div>
        <?php
        }
    ?>
</div>
