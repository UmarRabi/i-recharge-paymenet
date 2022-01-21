this system create customer, add a card for customer and charge customer

riutes
    {{domain}}api/customers/create
    method : post
    description : add a customer to the system and return a json response
    
    
    {{domain}}api/customers/get/{id} //the id on the path is the customer id
    method : get
    description : get a customer details with their cards and payment history
    
    {{domain}}api/cards/create/{id} //the id on the path is the customer id
    method : psot
    description : create a card and tie it to a customer record
    
    
