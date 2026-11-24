<?php

foreach (glob(__DIR__.'/modules/kepala/*.php') as $berkas) {
    require $berkas;
}
