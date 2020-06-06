@foreach($contracts as $contract)
<tr>
    <th scope="row"><?=$contract['id']?></th>
    <td><?=$contract['template']?></td>
    <td id="contract_<?=$contract['id']?>">
        <input type="text" class="not_input" value="<?=$contract['address']?>" readonly>
        <button class="btn btn-outline-success copy_btn">Скопировать</button>
    </td>
    <td><button class="btn btn-outline-info" onclick="showInstruction('<?=$contract['guide']?>')">Посмотреть</button></td>
    <td><?=$contract['created']?></td>
    <td><?=$contract['status']?></td>
    <td><button class="btn btn-outline-danger" onclick="removeContract(<?=$contract['id']?>)">Отозвать</button></td>
</tr>
@endforeach
