<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Product;
use App\Services\ProductServices;

#[AsCommand(
    name: 'app:product',
    description: 'Add a short description for your command',
)]
class ProductCommand extends Command
{
    public function __construct(private ProductServices $productServices)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
           ->addArgument('file', InputArgument::REQUIRED, 'Path to the CSV file containing products');
        
            /*->addOption('name', null, InputOption::VALUE_REQUIRED, 'Name product')
            ->addOption('description', null, InputOption::VALUE_OPTIONAL, 'Product description')
            ->addOption('price', null, InputOption::VALUE_REQUIRED, 'Product price')
            ->addOption('cover', null, InputOption::VALUE_OPTIONAL, 'Product cover')
            ->addOption('stock', null, InputOption::VALUE_OPTIONAL, 'Product stock')*/
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

         // Récupérer le chemin du fichier CSV
         $filePath = $input->getArgument('file');

          
        if (!file_exists($filePath)) {
            $io->error("The file '$filePath' does not exist.");
            return Command::FAILURE;
        }
        

        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ','); 
            
           
            $expectedHeaders = ['name', 'description', 'price', 'cover', 'stock'];
            if ($header !== $expectedHeaders) {
                $io->error('Invalid CSV headers. Expected: ' . implode(', ', $expectedHeaders));
                fclose($handle);
                return Command::FAILURE;
            }

            $io->info('Importing products...');
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $product = new Product();
                $product->setName($data[0]);
                $product->setDescription($data[1]);
                $product->setPrice((float) $data[2]);
                $product->setCover($data[3]);
                $product->setStock((int) $data[4]);
                
                $this->productServices->save($product);
            }

            fclose($handle);

            $io->success('Products imported successfully!');
            return Command::SUCCESS;
        }

        $io->error('Failed to open the file.');
        return Command::FAILURE;
        
        
       /* $name        = $input->getOption('name');
        $description = $input->getOption('description');
        $price       = $input->getOption('price');
        $cover       = $input->getOption('cover');
        $stock       = $input->getOption('stock');

        if( $name && $price){
            $product = new Product();

            $product-> setName($name);
            $product-> setDescription($description);
            $product-> setPrice($price);
            $product-> setCover($cover);
            $product-> setStock($stock);
           
            $this->productServices->save($product);
            $io->success("Product created with success!");
            exit(0);
        }

        $io->error("error to add product");
        return 1;*/
       
    }
}
