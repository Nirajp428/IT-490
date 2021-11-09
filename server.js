const express = require("express")
const cors = require('cors')
const mysql = require("mysql")
const app = express()
app.use(express.json());
app.use(cors())
const PORT = process.env.port || 4500

/*

const con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "123456",
    database: "nodemysql"
  });

con.connect((err) => {
    if (err) {
        throw err
    }
    console.log("MySql Connected...")

})

*/

app.post("/like", async (req, res) => {
    try {
        const {userID, movieTitle} = req.body

        // store the data in the database here

        return res.json({success: true})

    } catch (err) {

        return res.json({success: false})
    }
})

app.post("/dislike", async (req, res) => {
    try {
        const {userID, movieTitle} = req.body

        // store the data in the database here

        return res.json({success: true})

    } catch (err) {

        return res.json({success: false})
    }
})

app.listen(PORT, () => console.log("server listening on port " + PORT) )
