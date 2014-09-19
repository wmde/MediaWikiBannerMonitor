<?php

namespace BannerMonitor\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckBannersCommand extends Command {

    protected function configure() {
        $this
            ->setName( 'checkbanners' )
            ->setDescription( 'Checks the banners' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {
        
    }

}