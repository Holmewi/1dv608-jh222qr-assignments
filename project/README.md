1dv608-jh222qr-project
===========================

#Requirements

##System quality requirements
	


##Use-Cases

###UC1 Create new product
Main scenario

1. Admin wants to add a product to the database.
2. System asks for title, description, price, unique and an product image.
3. Admin fills in a form and adds the product image to it.
4. System validates the image and the input.
5. a) System stores the product data in the database and presents a success message.
6. System presents an alternativ to add category tags to the product (can be done later).
7. a) Admin selects categories from list and add them to the product.
8. System stores the categories to the product in the database.

Alternative scenarios

- 5b) Input or image is not valid
	- System presents an error message
	- Step 2 in main scenario.

- 7b) Admin finishes without adding a category

###UC2 Create new category tag
Main scenario

1. Admin wants to add a new category tag to the database
2. System asks for tag name
3. Admin gives the tag name
4. System validates the input.
5. a) System stores the tag name and presents a success message.

Alternative scenario

- 5b) Input is not valid.
	- System presents an error message.
	- Step 2 in main scenario.


###UC2 View a product
Main scenario

1. Admin wants to view a specific product
2. System presents a list of products
3. Admin clicks on a product to view it
4. System presents a view of the product

###UC3 Sort products by hashtag
Main scenario

1. Admin wants to sort products by a certain category tag
2. Admin clicks the category from a list
3. a) System lists all products from that category

Alternative scenario

- 3b) There are no products with that category
	- System presents a message saying the category is empty.

###UC4 Update product information
Main scenario

1. Admin wants to update a product
2. a) System lists all product
3. Admin clicks edit link for the product
4. System presents the information ready to edit as a text input
5. Admin updates the information
6. System validates the information
7. a) System stores the product data in the database and presents a success message.

Alternative scenarios

- 7b) Input or image is not valid
	- System presents an error message.
	- Step 4 in main scenario.

- 2b) Starts from UC2 step 4.
	- 3. Admin wants to edit a specific products information
	- 4. Step 4 in main scenario.

###UC5 Delete a product
Main scenario

1. Admin wants to delete a product
2. System lists all products
3. Admin clicks delete link for the product
4. System presents confirmation
5. a) Admin clicks ok
6. System deletes the product from the database and presents a success message

Alternative scenario

- 5b) Admin clicks cancel
	- System presents cancelation message
