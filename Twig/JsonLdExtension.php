<?php


namespace Piktalent\Bundle\JsonLDBundle\Twig;


use Piktalent\Bundle\JsonLDBundle\Listener\KernelListener;

class JsonLdExtension extends \Twig_Extension
{

    private $listener;

    public function __construct(KernelListener $listener)
    {
        $this->listener = $listener;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('json_ld', [$this, 'getFormatter'],
                [
                    'is_safe'           => ['html'],
                    'needs_environment' => true
                ])
        ];
    }


    public function getFormatter(\Twig_Environment $twig)
    {
        $view = $twig->render('PiktalentJsonLDBundle::formatter.html.twig', [
            'schema'=> $this->listener->getSchema()
        ]);
        return $view;
    }

    public function getName()
    {
        return 'json_ld_extension';
    }
}