<?php namespace Egig\Markdoc\Commands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Michelf\Markdown;
use Michelf\MarkdownInterface;

class GenerateCommand extends Command
{
    /**
     * Source directory which mardown files to be converted/generated
     *
     * @var string
     */
    protected $source;

    /**
     * Destination directory to put generated html.
     *
     * @var string
     */
    protected $destination;

    /**
     * Template file used to wrap the generated html. we'll use Twig.
     *
     * @var string
     */
    protected $template;

    /**
     * Filesystem lbrary used by application.
     *
     * @var Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Finder component used by application.
     *
     * @var Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * Templatin handler used by application.
     *
     * @var Twig_TemplateInterface
     */
    protected $templateHandler;

    /**
     * Markdown parser used by aplication
     *
     * @var Mitchelf\MarkdownInterface
     */
    protected $markdownParser;

    /**
     * Create command instance and setting up the option.
     *
     * @param array $config
     */
    public function __construct(
        Filesystem $filesystem,
        Finder $finder,
        \Twig_TemplateInterface $templateHandler,
        MarkdownInterface $markdownParser)
    {
        parent::__construct();
        $this->filesystem       = $filesystem;
        $this->finder           = $finder;
        $this->templateHandler  = $templateHandler;
        $this->markdownParser  = $markdownParser;
    }

    /**
     * {inheritdocs}
     *
     */
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('generate html docs from markdown files')
            ->addArgument(
                'source',
                InputArgument::OPTIONAL,
                'Source dir contains files to generate.'
            )
            ->addArgument(
                'destination',
                InputArgument::OPTIONAL,
                'Destination dir to put generated file to'
            )
        ;
    }

    /**
     * {inheritdocs}
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source         = $input->getArgument('source') ?: $this->source;
        $destination    = $input->getArgument('destination') ?: $this->destination;

        if( ! is_dir($destination) )

            $this->filesystem->mkdir( $destination );

        $this->finder->files()->name('*.md')->in( $source );

        foreach ($this->finder as $file) {

            $path = $file->getRealpath();

            if( $relPath = $file->getRelativePath() )
            {
                $this->filesystem->mkdir( $destination .'/'. $relPath );
            }
            
            $content = $this->markdownParser->transform( $file->getContents() );

            $html = $this->templateHandler->render(array('content' => $content));

            $filename = str_replace('.md','.html', $file->getRelativePathname());

            if(file_put_contents($destination.'/'.$filename, $html))     
                $output->writeln( 'generated: '. realpath($destination.'/'.$filename) );
        }
    }

    /**
     * Setup option.
     *
     * @param array $options
     * @return void
     */

    public function setupOptions( $options = array() )
    {
        $this->source       = $options['source'];
        $this->template     = $options['template'];
        $this->destination  = $options['destination'];
    }
}