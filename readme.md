# Update ENDSHARE 

Ever since we moved the Database to AWS Endicia Professional stopped updating the MOM intermediary table ENDSHARE.  
So before running End of Day Processing:

1. Go to [endicia.com](https://endicia.com) and download the shipments for the last day or two.  
2. Go to local site [endicia.loc](http://endicia.loc) and load that file.
---
3. Go to <abbr title="Mail Order Manager">M.O.M.</abbr> 
   * Order 
   * Process Orders 
   * Batch Order Processing
     * Uncheck all of the stages 
     * click on the "Import Shipping Data" File button 
     * check mark "Import Shipping Data from Universal Shipping Interface", close
     * click the "Process" button

Now the tracking number and ship date should appear in the box table.

```
    update ENDSHARE 
        set USI_STATUS='S', 
            TRACKINGNO='{$tracking_number}', 
            BILL_WEIGH='{$bill_weigh}', 
            SHIP_DATE='{$ship_date}', 
            CHARGES={$charges} 
            //SHIP_VIA=box.ship_via
        where ORDERNO='{$orderno}' and USI_STATUS = :usi_status 
```

