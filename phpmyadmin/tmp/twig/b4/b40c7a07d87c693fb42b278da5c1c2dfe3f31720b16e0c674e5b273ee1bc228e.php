<?php

/* list/unordered.twig */
class __TwigTemplate_c2f8fd57c1c33a68d7f59c6d22596dd3d1369fc7daf015961d786d83208cadab extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<ul";
        if ( !twig_test_empty((isset($context["id"]) ? $context["id"] : null))) {
            echo " id=\"";
            echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
            echo "\"";
        }
        // line 2
        if ( !twig_test_empty((isset($context["class"]) ? $context["class"] : null))) {
            echo " class=\"";
            echo twig_escape_filter($this->env, (isset($context["class"]) ? $context["class"] : null), "html", null, true);
            echo "\"";
        }
        echo ">

    ";
        // line 4
        if ( !twig_test_empty((isset($context["items"]) ? $context["items"] : null))) {
            // line 5
            echo "        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["items"]) ? $context["items"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 6
                echo "            ";
                if ( !twig_test_iterable($context["item"])) {
                    // line 7
                    echo "                ";
                    $context["item"] = array("content" => $context["item"]);
                    // line 8
                    echo "            ";
                }
                // line 9
                echo "            ";
                $this->loadTemplate("list/item.twig", "list/unordered.twig", 9)->display($context["item"]);
                // line 10
                echo "        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 11
            echo "    ";
        } elseif ( !twig_test_empty((isset($context["content"]) ? $context["content"] : null))) {
            // line 12
            echo "        ";
            echo (isset($context["content"]) ? $context["content"] : null);
            echo "
    ";
        }
        // line 14
        echo "</ul>
";
    }

    public function getTemplateName()
    {
        return "list/unordered.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  69 => 14,  63 => 12,  60 => 11,  54 => 10,  51 => 9,  48 => 8,  45 => 7,  42 => 6,  37 => 5,  35 => 4,  26 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "list/unordered.twig", "/home/wwwroot/default/phpmyadmin/templates/list/unordered.twig");
    }
}
