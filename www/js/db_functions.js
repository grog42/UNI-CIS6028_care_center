
var dbFunctions = {

    createDataObj: function(formId, inputNames){
        var data = {};

        for(var i = 0; i < inputNames.length; i++){
            data[inputNames[i]] = $(`#${formId} input[name=${inputNames[i]}]`).val();
        }
        
        return data;
    },

    makeRequest: function(query, inputData, callback=null){
        var userData = {username: "", password: ""};

        var request = {query: query, user: userData, input: inputData};
        
            $.ajax({ 
                type: "POST", 
                url: "/php/db_request.php", 
                //dataType: "text/html",
                data: request, 
                success: function (dataJSON) { 
        
                    var dataObj = JSON.parse(dataJSON);
                    console.log(dataObj);
        
                    if(dataObj.type === "error"){
                        alert(dataObj.msg);
                    }else if (callback != null){
                        callback(dataObj);
                    }
                },
                error: function(dataObj){
                    console.log("err");
                    console.log(dataObj);
                    $("#error-box").html(dataObj.responseText);
                }
            });
    },

    insertInto: function(table, data){
        dbFunctions.makeRequest('insert', {table: table, data: data});
    },
    
    removeById: function(table, id){
        dbFunctions.makeRequest('remove', {table: table, id: id});
    },
    
    updateById: function(table, id, data){
        dbFunctions.makeRequest('update', {table: table, id: id, data: data});
    },
    
    selectById: function(table, id, callback){
        dbFunctions.makeRequest('selectById', {table: table, id: id}, callback);
    },
    
    selectCount: function(table, callback){
        dbFunctions.makeRequest('selectCount', {table: table}, callback);
    },

    selectAll: function(table, limit, callback){
        dbFunctions.makeRequest('selectAll', {table: table, limit: limit}, callback);
    },
    
    selectWhere: function(table, conditions, limit, callback){
        dbFunctions.makeRequest('selectWhere', {table: table, conditions: conditions, limit: limit}, callback);
    },

    selectWhereActive: function(table, conditions, limit, callback){
        dbFunctions.makeRequest('selectWhereActive', {table: table, conditions: conditions, datetime: Date.now(), limit: limit}, callback);
    },

    selectWhereLessThan: function(table, conditions, limit, callback){
        dbFunctions.makeRequest('selectWhereLessThan', {table: table, conditions: conditions, limit: limit}, callback);
    },
    
    selectWhereFree: function(table, date, start, end, limit, callback){
    
        var conditions = {
            "date": date,
            "start_time": start,
            "end_time": end
        };
    
        dbFunctions.makeRequest('selectWhereFree', {table: table, conditions: conditions, limit: limit}, callback);
    },
    
    selectFreeStaff: function(date, start, end, callback){
        dbFunctions.selectWhereFree('staff', date, start, end, 20, callback);
    },
    
    selectFreeRooms: function(date, start, end, callback){
        dbFunctions.selectWhereFree('rooms', date, start, end, 20, callback);
    },
    
    selectFreeBeds: function(callback){
    
        var conditions = {
            "is_empty": 1
        };
    
        dbFunctions.selectWhere('beds', conditions, 20, callback);
    },
    
    selectRoomsInBuilding: function(building_id, callback){
    
        var conditions = {
            "building_id": building_id
        };
        selectWhere('rooms', conditions, 20, callback);
    },
    
    selectBedsFreeInRoom: function(room_id, callback){
        var conditions = {
            "room_id": room_id
        };
        selectWhere('beds', conditions, 20, callback);
    },
    
    selectRoomReservations: function(room_id, callback){
        var conditions = {
            "room_id": room_id
        };
    
        selectWhere('reservations', conditions, 20, callback);
    },
    
    selectPatientByName: function(firstName, lastname, callback){
        var conditions = {
            "firstName": firstName,
            "lastname": lastname
        };
    
        selectWhere('patients', conditions, 20, callback);
    }
}


