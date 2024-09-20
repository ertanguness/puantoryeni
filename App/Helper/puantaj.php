<?php 
require_once "Database/db.php";
require_once "Model/Projects.php";

$project = new Projects();


use Database\Db;
class puantajHelper extends Db{
    public function getPuantajTuruList($turu)
    {
       
        // SQL sorgusu ve verilerin alınması
        $sql = $this->db->prepare("SELECT * FROM puantajturu WHERE Turu = ? ORDER BY PuantajSaati ");
        $sql->execute(array($turu));

        // Başlangıç HTML
        $output = '<ul class="nav grid">';

        // Veritabanından gelen verilerle liste öğeleri oluşturma
        while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
            $output .= '
            <li class="nav-item" style="min-width:200px">
                <div class="user-block" >
                    <span class="avatar" data-tooltip="' . $result["PuantajSaati"] . ' Saat"  data-id="' . $result["id"] . '" style="background-color:' . htmlspecialchars($result["ArkaPlanRengi"])
                . ';color:' . $result["FontRengi"] . '">' . htmlspecialchars($result["PuantajKod"]) . '</span>
                    <span class="head-title">' . htmlspecialchars($result["PuantajAdi"]) . '</span>
                    <p class="description">' . htmlspecialchars($result["Turu"]) . '</p>
                </div>
            </li>';
        }

        // Kapanış HTML
        $output .= '</ul>';

        return $output;
    }

    function puantajClass($turu, $project = 0, $puantaj_project = "")
    {
        $pcq = $this->db->prepare("SELECT * FROM puantajturu WHERE id = ?");
        $pcq->execute(array($turu));
        $result = $pcq->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if ($result["PuantajKod"] == "HT") {
                $backcolor = $result["ArkaPlanRengi"];
                $color = $result["FontRengi"];
                $selected = "";
            } else {
                if($puantaj_project != $project) {
                    $backcolor = "#bbb";
                    $color = "#666";
                    $selected = "selected";
                } else {
                    $backcolor = $result["ArkaPlanRengi"];
                    $color = $result["FontRengi"];
                    $selected = "";
            }
            } 
            echo "<td class='gun noselect $selected' data-change='false'  data-project='" . $puantaj_project . "' data-id=" . $result["id"] . " style='background:".$backcolor.";color:".$color."'>" . $result["PuantajKod"] . "</td>";
        } else {
            echo "<td class='gun noselect' data-change='false' data-project='0'></td>";
        }
    }
}