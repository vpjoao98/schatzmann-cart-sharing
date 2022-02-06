## Schatzmann_CartSharing
***
### Description
Schatzmann_CartSharing provides to customers the possibility to share their carts through a generated url.

### Configuration
To enable Cart Sharing functionality, make sure that **Schatzmann_Base** is enabled and then go to `Store > Configuration > Schatzmann > Cart Sharing > Enable Cart Sharing` and set to `yes`.

![configurations](https://i.imgur.com/vw95DBf.png)

<details>
<summary>Additional Configurations</summary>
<p>

1. #### Validate Cart Sharing Key
If enabled, the cart sharing key that customers insert into the checkout/cart page will be validated. Only validated keys will generate the shared cart url.

2. #### Show Cart Sharing as Popup
If enabled, the form shown in checkout/cart will be presented as popup.

3. #### Cart Sharing Key Length
It is possible to define the number of characters that the Cart Sharing Key will have. By default, it is set to 6. If the value changes, the customers that already have a key will generate a new one when saved.

4. #### Enable functionality for following Customer Groups
Select the customer groups that will have access to this functionality.

5. #### Change access to this functionality to specific customers
It is possible to provide or revoke access to specific customers by changing their Can Share Cart's attribute in the customer form.

![can share cart](https://i.imgur.com/OQqc4Qc.png)

</p>

</details>

### Cart Sharing Flow
The cart sharing flow is very similar for both authenticated and non-authenticated. The main difference is that the customer's sharing key will be validated when they try to share their carts.

<details>
<summary>Flow Explanation</summary>
<p>

- The cart sharing will be enabled to guest customers if the **NOT LOGGED IN** group is selected on **Enable functionality for following Customer Groups**.
- If **Not Logged In group is selected** and the **key validation is enabled**, the shared cart will receive a **Shared By** attribute to show who shared their cart.
- When creating a customer account, a **Cart Sharing Key** is automatically generated for the customer based on **Cart Sharing Key Length**.
- The customer can see their **Cart Sharing Key** in **customer's dashboard** and customer's form on **administration panel**.
- In **customer's dashboard**, is possible to see the **Recent Shared Carts**, and in the dashboard navigation will have a link to the **Shared Carts History**, showing all active shared cart's urls.
- Once accessing the **checkout/cart**, customers will see a **Share Cart** button together with Discounts form.
    - By default, the **Cart Sharing Key** input will be shown the same as the discount input;
    - If the **Popup** option is enabled, this button will show a popup asking customer to provide its key.
- After filling the input and clicking **Generate URL**, a **Cart Sharing Url** will appear.
    - If validation is enabled, the server will validate its key and return an error or the **Cart Sharing Url**.
- The customer will have 15 seconds to copy the link before get redirected to Home page. This action is take to make sure that the current customer will not have access to its former cart.
- The generated url will be presented to the customer in the account dashboard until the link is accessed. They will also have access to all the active shared carts url into the **My Shared Carts** page.
- The generated url can be accessed by any customer and, once clicked, will be created a copy of the shared cart's items and coupons.

</p>

</details>

### Images

<details>
<summary>Desktop</summary>
<p>

![cart sharing button](https://i.imgur.com/ez4gHkd.png)

![popup](https://i.imgur.com/n2CI3kL.png)

![url successfully generated](https://i.imgur.com/FKQoy27.png)

![dashboard](https://i.imgur.com/NUKs6RV.png)

![my shared carts](https://i.imgur.com/EeQsve1.png)

</p>

</details>

<details>
<summary>Mobile</summary>
<p>

![cart sharing button mobile](https://i.imgur.com/X34TPPY.png)

![popup mobile](https://i.imgur.com/jKDVuNw.png)

![url successfully generated mobile](https://i.imgur.com/0eqZnYt.png)

![dashboard mobile](https://i.imgur.com/T0gEL5e.png)

![my shared carts mobile](https://i.imgur.com/KuMeJWS.png)

</p>

</details>

### Contact
If you have any doubt or suggestions, contact me by [E-mail](vpjoao98@gmail.com) or send me a message on [LinkedIn](https://www.linkedin.com/in/joaovp/).
