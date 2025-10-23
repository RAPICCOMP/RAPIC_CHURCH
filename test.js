import React, { useState } from "react";
import { motion } from "framer-motion";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";

export default function GivingOptions() {
  const [showDetails, setShowDetails] = useState(true);

  const handleToggle = () => {
    setShowDetails((prev) => !prev);
  };

  const cardVariants = {
    hidden: { scale: 0.9, opacity: 0 },
    visible: { scale: 1, opacity: 1, transition: { duration: 0.5 } },
    zoomOut: { scale: 0.5, opacity: 0, transition: { duration: 0.5 } },
  };

  return (
    <div className="p-4 grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
      <div className="col-span-full text-center mb-4">
        <h1 className="text-2xl font-bold">Ways to Give</h1>
        <p className="text-gray-600">Choose any of the convenient ways to contribute to the church.</p>
        <Button className="mt-4" onClick={handleToggle}>
          {showDetails ? "Zoom Out Details" : "Show Details"}
        </Button>
      </div>

      {showDetails && (
        <>
          <motion.div
            variants={cardVariants}
            initial="hidden"
            animate="visible"
            exit="zoomOut"
            className="bg-white shadow rounded-lg p-4"
          >
            <Card>
              <CardHeader>
                <CardTitle>Online Giving</CardTitle>
              </CardHeader>
              <CardContent>
                <p>Use our secure online portal to give conveniently from anywhere.</p>
              </CardContent>
            </Card>
          </motion.div>

          <motion.div
            variants={cardVariants}
            initial="hidden"
            animate="visible"
            exit="zoomOut"
            className="bg-white shadow rounded-lg p-4"
          >
            <Card>
              <CardHeader>
                <CardTitle>Mobile Money</CardTitle>
              </CardHeader>
              <CardContent>
                <p>Send your contributions via mobile money to our official number.</p>
              </CardContent>
            </Card>
          </motion.div>

          <motion.div
            variants={cardVariants}
            initial="hidden"
            animate="visible"
            exit="zoomOut"
            className="bg-white shadow rounded-lg p-4"
          >
            <Card>
              <CardHeader>
                <CardTitle>Bank Transfer</CardTitle>
              </CardHeader>
              <CardContent>
                <p>Directly transfer your offering to our church bank account.</p>
              </CardContent>
            </Card>
          </motion.div>

          <motion.div
            variants={cardVariants}
            initial="hidden"
            animate="visible"
            exit="zoomOut"
            className="bg-white shadow rounded-lg p-4"
          >
            <Card>
              <CardHeader>
                <CardTitle>Cash or Check</CardTitle>
              </CardHeader>
              <CardContent>
                <p>Drop off your cash or check during service or at the church office.</p>
              </CardContent>
            </Card>
          </motion.div>

          <motion.div
            variants={cardVariants}
            initial="hidden"
            animate="visible"
            exit="zoomOut"
            className="bg-white shadow rounded-lg p-4"
          >
            <Card>
              <CardHeader>
                <CardTitle>Giving Kiosk</CardTitle>
              </CardHeader>
              <CardContent>
                <p>Use our in-person giving kiosks available in the church lobby.</p>
              </CardContent>
            </Card>
          </motion.div>

          <motion.div
            variants={cardVariants}
            initial="hidden"
            animate="visible"
            exit="zoomOut"
            className="bg-white shadow rounded-lg p-4"
          >
            <Card>
              <CardHeader>
                <CardTitle>Other Options</CardTitle>
              </CardHeader>
              <CardContent>
                <p>Contact the church office for additional ways to give.</p>
              </CardContent>
            </Card>
          </motion.div>
        </>
      )}
    </div>
  );
}
