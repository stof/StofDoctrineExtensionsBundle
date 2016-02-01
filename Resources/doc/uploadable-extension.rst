Uploadable extension
====================

If you want to use the Uploadable extension, first read the
`official DoctrineExtensions documentation`_. Once everything is ready, use the
Form component as usual. Then, after you verify the form is valid, do the following:

.. code-block:: php

    $document = new Document();
    $form = $this->createFormBuilder($document)
        ->add('name')
        ->add('myFile')
        ->getForm()
    ;

    $form->handleRequest($request);

    if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($document);

        $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

        // Here, "getMyFile" returns the "UploadedFile" instance that the form bound in your $myFile property
        $uploadableManager->markEntityToUpload($document, $document->getMyFile());

        $em->flush();

        $this->redirect($this->generateUrl(...));
    }

    return $this->render('...', array('form' => $form->createView()));

And that's it. The Uploadable extension handles the rest of the stuff. Remember
to read its documentation!

.. _`official DoctrineExtensions documentation`: https://github.com/Atlantic18/DoctrineExtensions/tree/master/doc/
