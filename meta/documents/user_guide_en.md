# Product Information

With this plugin, you enable your customers to select a preferred delivery date from up to seven Saturdays.

You can define a surcharge for this service, which is automatically added to the shipping costs when you select weekend delivery. The user input is then created as an order property and can be read there by subsequent shipping plugins (you can find a list of currently compatible shipping plugins at the end of this document).

## Installation Guide

To select Saturday delivery, you must enter the appropriate values ​​in the plugin configuration.

1. Open the **Plugins » Plugin set overview** menu.
2. Select the desired plugin set.
3. Click on **Show free goodie in shopping cart**.<br>→ A new view opens.
4. Select the **Global** section from the list.
5. Enter a comma-separated list of IDs under _Allowed shipping profiles_ and the _Surcharge (gross)_.
7. **Save** the settings.

<div class="alert alert-info" role="alert">
   If you do not enter anything in the input field for allowed shipping profiles, Saturday delivery will be displayed for all shipping options.
</div>

Then create the container links so that the Saturday delivery area is also displayed in the front end of your plentyShop:

1. Change to the submenu **Container links**.
2. Associate the **Saturday Delivery CSS** content with the **Ceres::Template.Style** container
3. Associate the **Display Date Picker for Saturday Delivery** content with the **Ceres::Checkout.AfterShippingProfileList** container for display in the checkout (_Checkout: After shipping method_)

### More configuration options

| Setting                        | Description |
|------------------------------------|---------------|
| Lead time in days | Time span between shipping date and delivery. Example: With a lead time of 2 days, only the Saturday of the following week can be selected on Friday. On Wednesday, however, the coming Saturday would be possible. |
| Number of selection options | Determine how many Saturdays you want to offer in advance. Note: the maximum number is limited to 7. |

Table 1: More configuration options

### Compatible shipping plugins

According to the current state of knowledge, the following plugins are compatible and evaluate the date when it is handed over to the shipping service provider:

* [GO! Express](https://marketplace.plentymarkets.com/goexpress_55126) (from version 1.0.9)
* [eCourier](https://marketplace.plentymarkets.com/bambooecourier_55144), e.g. for shipping with DER KURIER (from version 1.0.4)

External developers are welcome to contact me for technical specifications so this list can be expanded.


<sub><sup>Every single purchase helps with constant further development and the implementation of user requests. Thanks very much!</sup></sub>
