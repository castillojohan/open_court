const getData = {

    requestData: async () =>{
        const response = await fetch('http://127.0.0.1:8000/account/admin/data', 
        {
            'method': 'GET',
            'header': {
                'Content-Type': 'application/json'
            },
        });
        const datas = await response.json();
        return datas.datas;
    }
}
export default getData;