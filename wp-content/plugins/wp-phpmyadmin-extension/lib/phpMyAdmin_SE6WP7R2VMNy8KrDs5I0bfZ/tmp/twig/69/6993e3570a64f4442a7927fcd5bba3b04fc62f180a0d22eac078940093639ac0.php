<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* display/export/option_header.twig */
class __TwigTemplate_0e8adc1fdacc90798b06ef4ac39e17b2caebfdbcc0a405d8b26e27ba958ef73b extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<div class=\"exportoptions\" id=\"header\">
    <h2>
        ";
        // line 3
        echo PhpMyAdmin\Util::getImage("b_export", _gettext("Export"));
        echo "
        ";
        // line 4
        if ((($context["export_type"] ?? null) == "server")) {
            // line 5
            echo "            ";
            echo _gettext("Exporting databases from the current server");
            // line 6
            echo "        ";
        } elseif ((($context["export_type"] ?? null) == "database")) {
            // line 7
            echo "            ";
            echo twig_escape_filter($this->env, sprintf(_gettext("Exporting tables from \"%s\" database"), ($context["db"] ?? null)), "html", null, true);
            echo "
        ";
        } else {
            // line 9
            echo "            ";
            echo twig_escape_filter($this->env, sprintf(_gettext("Exporting rows from \"%s\" table"), ($context["table"] ?? null)), "html", null, true);
            echo "
        ";
        }
        // line 11
        echo "    </h2>
</div>
";
    }

    public function getTemplateName()
    {
        return "display/export/option_header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  65 => 11,  59 => 9,  53 => 7,  50 => 6,  47 => 5,  45 => 4,  41 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "display/export/option_header.twig", "/opt/bitnami/apps/wordpress/htdocs/wp-content/plugins/wp-phpmyadmin-extension/lib/phpMyAdmin_SE6WP7R2VMNy8KrDs5I0bfZ/templates/display/export/option_header.twig");
    }
}
