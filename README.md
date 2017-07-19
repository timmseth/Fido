# FiDo

## License
FiDo is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License (version 3) as published by the Free Software Foundation.

FiDo is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with FiDo.  
If not, see [http://www.gnu.org/licenses/](http://www.gnu.org/licenses/).

## Purpose
FiDo can store, analyze, and help you make better decisions about your fiber optic resources. Using a MySql and PHP backend with the latest technologies driving the frontend, this web application can be put on most standard web servers. Once set up, this allows you to access your data from anywhere, and because Fido is tablet friendly this includes out in the field.

For a more information, keep reading.

## Features
* Simple, intuitive, and tablet-ready interface.
* Easy set up and configuration.
* Provides a centralized record (easy automated backups).
* Works great with PhpMyAdmin.
* Automatically calculates most efficient route for new fiber.
* Automatically calculates loss and cable requirements.
* Allows for comparison of "optimal conditions" and current conditions.
* Modular design allows for quick customization of individual sections of the front end.


## What it accomplishes:
### Complete C.R.U.D. Management of Fiber Optic Resources.
C.R.U.D. stands for "Create. Read. Update. Delete.".<br />
Resources are organized hierarchically as follows:
```
Building
  |Level (Floor)
    |Location (Room)
      |Storage_Unit (Rack)
        |Cabinet (Fiber Module Housing)
          |Panel (Fiber Module)
            |Port (Fiber Port)
              |Strand (Individual Fiber Strands Connected to Port Boot)
              |Jumper (Individual Fiber Strands Connected to Port Front)
```
This design relies on a relational MySQL database that ties everything together on the backend. Once all the information has been entered into FiDo these relations allow for easy "drill down" when troubleshooting problems or exploring your resources.

### Attenuation Calculation & Storage
When was the last time you tested your fiber? FiDo can tell you. Do you have fiber that needs to be replaced? Fido can tell you. FiDo stores and checks the attenuation every time you test your fiber. Using manufacturer standards FiDo will automatically compare the measured attenuation to the expected attenuation and display whether or not stands are in tolerance.

### Logical Mapping
We use PHP and javascript to dynamically build a map of your resources as you enter information. With zero set up, you will see your logical network layout updated with YOUR information. The goal of this is to have a page where you can SHOW instead of tell the layout of your network.

### GIS Mapping
This is a work in progress. Using the Google Maps API we want to have detailed satellite imagery with layers containing YOUR information. The goal of this is to have a page where you can SHOW instead of tell the paths your fiber follows. Need a new cable pulled? Print out detailed images of the path from start to finish.

### Mapping Building Interiors Level by Level
This is a work in progress. Our current plan is to allow users to map out every pathway and resources on each floor in a building and store this information in a database with floor maps on the backend. This is going to be a challenge, but if we manage it FiDo will be even more useful.

### Intelligent Data Display
A big focus on FiDo is giving you intelligent data. Intelligent Data means we don’t just use tables to spew all your information at you. We want you to have everything you need to make a good decision.

We show you what you need when you need it:
* Responsive Tables
* Dynamic Graphs
* Maps with Data Overlays

You end up spending less time digging through useless information and more time making intelligent decisions.

### Dynamic Reports
Using intelligent data (we talked about that already) FiDo generates reports to tell you what you WANT to know. We are working on making this feature more customizable at the moment, but already we can see the rate at which new information is being added to the database and the distribution of that information. We're excited to add more reporting features in the future.

### More Information
For more information about FiDo, contact Seth Timmons at timmseth@isu.edu .

Thanks for caring about FiDo!
