<?php
// TODO FIX CSS table

$remarkCodes = [
    'ON_TIME' => 'Flight is expected to arrive/depart on time',
    'BOARDING' => 'Flight is currently boarding',
    'CANCELLED' => 'Flight has been cancelled',
    'DIVERTED' => 'Flight has been diverted',
    'ARRIVED_ON_TIME' => 'Flight has made an on-time arrival',
    'ARRIVAL_DELAYED' => 'Flight is projected to arrive late',
    'ARRIVED_LATE' => 'Flight has arrived, but later than scheduled',
    'DEPARTED_ON_TIME' => 'Flight has made an on-time departure',
    'DEPARTURE_DELAYED' => 'Flight is projected to depart late',
    'DEPARTED_LATE' => 'Flight has departed, but later than scheduled',
    'UNKNOWN' => 'No remarks information available for this flight',
];

$imageElementTypes = ['airlineLogoUrlPng', 'airlineLogoUrlSvg'];
$arrayElementTypes = ['codesharesAsNames', 'codesharesAsCodes', 'uplineAirportNames', 'uplineAirportCodes', 'downlineAirportNames', 'downlineAirportCodes']

?>
<!--PLUGIN STARTS FROM HERE-->
<div class="fids-wrapper">
    <div class="fids-headers">
        <?php
        foreach ($headersWithCustomTitle as $h) {
        ?>
            <div class="fids-header <?php echo 'fids-header__' . $h['key'] ?>">
                <?php echo $h['title'] ?>
            </div>
        <?php
        }
        ?>
    </div>
    <div class="fids-rows">
        <?php
        foreach ($data as $d) {
        ?>
            <div class="fids-row">
                <?php
                foreach ($headersWithCustomTitle as $hwt) {
                ?>
                 <div class="fids-row-element <?php echo 'fids-row-element__' . $hwt['key']?>">
                     <?php
                     if(in_array($hwt['key'], $imageElementTypes)) {
                     ?>
                         <img src="<?php echo $d[$hwt['key']] ?>" alt="">
                     <?php
                     } else if(in_array($hwt['key'], $arrayElementTypes)) {
                         if(isset($d[$hwt['key']])) {
                             $str = implode(',', $d[$hwt['key']]);
                             echo $str;
                         } else {
                             echo ''; // no data available
                         }
                     } else if ($hwt['key'] == 'remarksCode') {
                         if(isset($d[$hwt['key']])) {
                             echo $remarkCodes[$d[$hwt['key']]];
                         } else {
                             echo ''; // No remark code
                         }
                     } else {
                         echo isset($d[$hwt['key']]) ? $d[$hwt['key']] : 'Data not available';
                     }
                     ?>
                 </div>
                <?php
                }
                ?>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<!--PLUGIN ENDS HERE-->