<?php
/**
 * ifotec.inc.php
 *
 * LibreNMS os poller module for Ifotec devices
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    LibreNMS
 * @link       http://librenms.org
 * @copyright  LibreNMS contributors
 * @author     Cedric MARMONIER
 */

namespace LibreNMS\OS;

use Illuminate\Support\Str;
use LibreNMS\Interfaces\Discovery\OSDiscovery;
use LibreNMS\OS;

class Ifotec extends OS implements OSDiscovery
{
    public function discoverOS(): void
    {
        //echo "#### Call discoverOS ifotec.php #########################################################\n";
        $device = $this->getDeviceModel();

        //echo "  sysObjectID : " . $device->sysObjectID . "\n";
        if (Str::startsWith($device->sysObjectID, '.1.3.6.1.4.1.21362.100.')) {
            $ifoSysProductIndex = snmp_get($this->getDevice(), '.1.3.6.1.4.1.21362.101.1.1.0', '-Oqv');
            //echo "  ifoSysProductIndex : " . $ifoSysProductIndex . "\n";
            if ($ifoSysProductIndex != null) {
                $ifoSysSoftware   = snmp_get($this->getDevice(), '.1.3.6.1.4.1.21362.101.1.2.1.7.' . $ifoSysProductIndex, '-Oqv');
                $ifoSysBootloader = snmp_get($this->getDevice(), '.1.3.6.1.4.1.21362.101.1.2.1.8.' . $ifoSysProductIndex, '-Oqv');
                $device->version  = $ifoSysSoftware . " (Bootloader " . $ifoSysBootloader . ")";

                $device->serial   = snmp_get($this->getDevice(), '.1.3.6.1.4.1.21362.101.1.2.1.5.' . $ifoSysProductIndex, '-Oqv');
            }
        } else {
            $ifoSysProductIndex = 0;
        }

        // sysDecr = (<product_reference> . ' : ' . <product_description>) OR (<product_reference>)
        list($device->hardware) = explode(' : ', $device->sysDescr, 2);

        //echo "#### END discoverOS ifotec.php #########################################################\n\n";
    }
}
