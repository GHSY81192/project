<div class="product-msg">
    <div class="table-msg">
        <table width="100%" border="0">
            <tr>
                <th width="50%" height="40" scope="col"><span class="l-con">产品名称</span></th>
                <th width="15%" scope="col">入住日期</th>
                <th width="15%" scope="col">离店日期</th>
                <th width="10%" scope="col">房间数量</th>
                <th width="10%" scope="col">总价</th>
            </tr>
            <tr>
                <td height="40"><span class="l-con">{$info['productname']}</span></td>
                <td>{$info['usedate']}</td>
                <td>{$info['departdate']}</td>
                <td>{$info['dingnum']}</td>
                <td><i class="currency_sy">{Currency_Tool::symbol()}</i>{$info['totalprice']}</td>

            </tr>

        </table>
    </div>
</div>