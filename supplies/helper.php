<?php

    function queryGroups()
    {
        global $db;

        $groupSQL = $db->query("SELECT * FROM SuppliesItemGroups");
        if (!$groupSQL)
            die("Failed to connect to database. ".$db->error);

        $groups = array();
        while ($record = $groupSQL->fetch_assoc())
            $groups[$record['ID']] = $record;

        $groupSQL->close();
        return $groups;
    }



    function queryItems($sectionID)
    {
        global $db;

        $itemSQL = $db->query("SELECT * FROM Supplies WHERE sectionID=$sectionID ORDER BY itemID");
        if (!$itemSQL)
            die("Failed to fetch data. ".$db->error);

        $items = array();
        while ($record = $itemSQL->fetch_assoc())
            array_push($items, $record);

        $itemSQL->close();
        return $items;
    }


    function groupItems($items, $groups)
    {
        $groupedItems = array();
        foreach ($groups as $groupID => $group)
        {
            $groupedItems[$groupID] = array();
            foreach ($items as $item)
                if ($item['groupID'] == $groupID)
                    array_push($groupedItems[$groupID], $item);
        }

        return $groupedItems;
    }


    function printSection($sectionRecord, $groups)
    {
        echo '
        <div class="subtitle">'.$sectionRecord['name'].'</div>
        <table>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price per unit</th>
                <th></th>
            </tr>
            ';

        $items = queryItems($sectionRecord['ID']);
        $groupedItems = groupItems($items, $groups);

        foreach ($items as $item)
        {
            if ($item['groupID'] > 0)
            { //part of a group
                if (!empty($groupedItems[$item['groupID']]))
                { //part of a valid group and group hasn't already been printed

                    echo '
                        <tr>
                            <td></td>
                            <td>'.$groups[$item['groupID']]['name'].'<br>'
                                 .$groups[$item['groupID']]['description'].'
                            </td>
                            <td></td>
                            <td></td>
                        </tr>';

                    foreach ($groupedItems[$item['groupID']] as $subItem)
                    {
                        echo '
                        <tr>
                            <td></td>
                            <td>
                                <div class="groupedTD">'
                                    .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$subItem['name'].'<br>'
                                    .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$subItem['description'].'
                                </div>
                            </td>
                            <td>
                                <div class="groupedTD">
                                $'.$subItem['price'].'
                                </div>
                            </td>
                            <td>
                                <div class="groupedTD">
                                <input name="'.$subItem['itemID'].'" type="number" min="0" value="0">
                                </div>
                            </td>
                        </tr>';
                    }

                    unset($groupedItems[$item['groupID']]);
                }
            }
            else
            {
                echo '
                <tr>
                    <td></td>
                    <td>'.$item['name'].'</td>
                    <td>$'.$item['price'].'</td>
                    <td><input name="'.$item['itemID'].'" type="number" min="0" value="0"></td>
                </tr>';
            }
        }

        echo '
            </table>';
    }

?>
