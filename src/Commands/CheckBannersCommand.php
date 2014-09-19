<?php

namespace BannerMonitor\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Kai Nissen
 * @author Christoph Fischer
 */
class CheckBannersCommand extends Command {

    protected function configure() {
        $this
            ->setName( 'checkbanners' )
            ->setDescription( 'Checks the banners' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {
        
    }

}