# Create/Edit your personal translations with a form

We'll extend the example of the Category entity extract from the [personal translations feature](/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md#personal-translations)

You'll have to create a generic translation form, that you'll can reuse with your different entities:

``` php
<?php
namespace Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
 
class TranslationType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('locale')
            ->add('field')
            ->add('content')
        ;
    }
 
    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation'
        );
    }
 
    public function getName()
    {
        return 'translation';
    }
}
```

And link it to your entities with a collection field:

``` php
<?php
namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('translations', 'collection', array(
                'type' => new TranslationType(),
                'allow_add' => true,
                'by_reference' => false,
                'options' => array(
                    'data_class' => 'Entity\CategoryTranslation'
                )
            ))
        ;
    }
 
    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Entity\Category'
        );
    }
 
    public function getName()
    {
        return 'categ';
    }
}
```

It still have to develop the desired implementation in your template, dynamically with the prototype property of the translations field and some javascript codes, or statically.
You can find an example of this last example [here](/webda2l/category-translation)