<select id="select_template" onchange="getTemplate(this.value)" class="custom-select custom-select-lg mb-3 select-contract">
    <option selected value="0">Выберите шаблон контракта..</option>
    @php
        foreach ($templates as $template){
            echo '<option value="'.$template['id'].'">'.$template['name'].'</a>';
        }
    @endphp
</select>
